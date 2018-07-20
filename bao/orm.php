<?php

class orm
{
    public $sql = [
        'select' => 'select ',
        'from' => ['from ',[]],
        'where' => ' where ',
        'orderby' => ' order by ',
        'insertinto' => 'insert into ',
        'insertfields' => '',
        'values' => 'values'
    ];

    function select(){
        $fields = func_get_args();
        foreach ($fields as $field){
            if(is_array($field))
            {
                $fieldKey = key($field);
                $this->_add(__FUNCTION__,$this->_prefix($fieldKey).'.'.$field[$fieldKey]);
            }
            else {
                $this->_add(__FUNCTION__,$field);
            }

        }
        return $this;
    }

    function from($tableNames)
    {
        if(is_array($tableNames))
        {
            if(count($tableNames)<2) return $this;
            $tb1 = current($tableNames);
            $tb2 = next($tableNames);

            $tb1Key = key($tb1); $tb1Value = $tb1[$tb1Key];
            $tb2Key = key($tb2); $tb2Value = $tb2[$tb2Key];

            $this->_add(__FUNCTION__, $tb1Key);
            $this->_add(__FUNCTION__, $tb2Key);

            $whereStr = ' _'.$tb1Key.'.'.$tb1Value.'='.'_'.$tb2Key.'.'.$tb2Value;
            $this->where($whereStr);
        }else{
            $this->_add(__FUNCTION__,$tableNames);
        }
        return $this;
    }

    function where($s)
    {
        $this->_add(__FUNCTION__, $s, ' and ');
        return $this;
    }

    function orderby($str, $order='')
    {
        $order = ' '.$order;
        if(is_array($str))
        {
            $tb = key($str);
            $this->_add(__FUNCTION__,$this->_prefix($tb).'.'.$str[$tb]);
        }
        else
        {
            $this->_add(__FUNCTION__,$str.$order);
        }
        return $this;
    }


    function insert()
    {
        $params = func_get_args();
        $fields = $fieldsValues = $callback = [];
        foreach($params as $param)
        {
            if(is_array($param)){
                foreach ($param as $item)
                $field = key($item);
                $fieldValue = $item[$field];
                $fields[] = $field;
                if(is_string($fieldValue)) $fieldValue = "'".$fieldValue."'";
                $fieldsValues[] = $fieldValue;
            }
            $this->_add('insertfields','('.implode($fields,',').')');
            $this->_add('values','('.implode($fieldsValues,',').')');

            if(is_string($param)){
                $this->_add('into',$param);
            }
            if(is_bool($param) && $param){
                $this->db->beginTransaction();
            }
            if(is_callable($param)){
                $callback[] = $param;
            }
        }

        if(count($callback) > 0){
            $this->exec();
            $this->clearConfig();
            foreach ($callback as $call){
                $call = Closure::bind($call,$this);
                $call();
            }
        }

        return $this;

    }

    function _add($key, $field, $split=',')
    {
        if(!$this->sql) return;
        if(is_array($this->sql[$key]))
        {
            if(!in_array($field, $this->sql[$key][1]))
            {
                $this->sql[$key][1][] = $field;
            }
        }
        else
        {
//            if(trim($this->sql[$key]) != $key)
            if(preg_replace('/\s/','',$this->sql[$key]) != $key)
                $this->sql[$key] .= $split;
            $this->sql[$key] .= $field;
        }
    }

    function _prefix($tbName)
    {
        return ' _'.$tbName;
    } 


    function __toString()
    {
//        return implode($this->sql);
        global $map;
        $map = Closure::bind($map, $this, 'orm');

        $filter = function ($value, $key){
            if(!is_string($value)) return true;
            if(preg_replace('/\s/','',$value) == $key)
                return false;
            return true;
        };

        $this->sql = array_filter($this->sql,$filter,ARRAY_FILTER_USE_BOTH);

        $ret = array_map($map, array_values($this->sql));
        return implode($ret,' ');
    }
}

$map = function ($items)
{
    if(!is_array($items)) {return $items;}
    else
    {
        $res = '';
        foreach ($items[1] as $item)
        {
            if($res != '') $res .= ',';
            $res .= $item.orm::_prefix($item);
        }
        return $items[0] . $res;
    }
};

$orm = new orm();
//echo $orm->select(['news'=>'id'],'id','name','age')
//->from([['news'=>'classid'],['news_class'=>'id']])->orderby('id desc');

echo $orm->insert([
        ['username'=>'lisi'],
        ['phone'=>'133*******'],
        ['pwd'=>md5('123')],
],'users');

?>



<script>
    var str = document.body.innerHTML;
    var arrMatches = str.match(/(where)|(from)|(order\sby)|(select)|(limit)/g);

    arrMatches.forEach(function (item) {
        str = str.replace(item,"<span style='color:red'>"+ item +"</span>");
    });

document.body.innerHTML = str;

</script>