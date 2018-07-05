<?php

class orm
{
    public $sql = [
        'select' => 'select ',
        'from' => ['from ',[]]
    ];

    function select(){
        $fields = func_get_args();
        foreach ($fields as $field){
            $this->_add(__FUNCTION__,$field);
        }
        return $this;
    }

    function from()
    {
        $fields = func_get_args();
        foreach ($fields as $field){
            $this->_add(__FUNCTION__,$field);
        }
//        $this->sql[__FUNCTION__] .= $tableName;
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
                  $res .= $item;
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
    ->from('user')
    ->select('sex')
->from('news');