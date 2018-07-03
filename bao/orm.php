<?php

class orm
{
    public $sql = [
        'select' => 'select ',
        'from' => ' from '
    ];
    function select(){
        $fields = func_get_args();
        foreach ($fields as $field){
            $this->sql['select'] .= $field . ',';
        }
        return $this;
    }


    function from($tableName){
        $this->sql['from'] .= $tableName;
        return $this;
    }

    function __toString(){
        return implode($this->sql);
    }
}

$orm = new orm();
echo $orm->select('uid','name','age')
    ->from('user')
    ->select('sex')
->from('news');