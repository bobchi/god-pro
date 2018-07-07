<?php

class orm
{
    public $sql = [
        'select' => 'select ',
        'from' => ['from ',[]],
        'where' => ' where '
    ];

    function select(){
        $fields = func_get_args();
        foreach ($fields as $field){
            $this->_add(__FUNCTION__,$field);
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
//        $fields = func_get_args();
//        foreach ($fields as $field){
//            $this->_add(__FUNCTION__,$field);
//        }
//        $this->sql[__FUNCTION__] .= $tableName;
        return $this;
    }

    function where($s)
    {
        $this->_add(__FUNCTION__, $s, ' and ');
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
            if(trim($this->sql[$key]) != $key)
                $this->sql[$key] .= $split;
            $this->sql[$key] .= $field;
        }
    }

    function __toString(){
//        return implode($this->sql);
        $map = function ($items)
        {
          if(!is_array($items)) {return $items;}
          else
          {
              $res = '';
              foreach ($items[1] as $item)
              {
                  if($res != '') $res .= ',';
                  $res .= $item.' _'.$item;
              }
              return $items[0] . $res;
          }
        };

        $ret = array_map($map, array_values($this->sql));
        return implode($ret,' ');
    }
}

$orm = new orm();
echo $orm->select('uid','name','age')
->from([['news'=>'classid'],['news_class'=>'id']]);