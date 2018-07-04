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
            if(trim($this->sql[__FUNCTION__]) != __FUNCTION__)
            $this->sql[__FUNCTION__] .= ',';
            $this->sql[__FUNCTION__] .= $field;
        }
        return $this;
    }

    function from(){
        $fields = func_get_args();
        foreach ($fields as $field){
            if(trim($this->sql[__FUNCTION__]) != __FUNCTION__)
                $this->sql[__FUNCTION__] .= ',';
            $this->sql[__FUNCTION__] .= $field;
        }
//        $this->sql[__FUNCTION__] .= $tableName;
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