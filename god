#!/usr/local/php/bin/php
<?php

require('god_func' . substr(PHP_VERSION, 0 , 1));
//require('godinit.php');
use core\god_init;
function __autoload($className){
    $className = str_replace('\\', '/', $className) . '.php';
    require($className);
}
$result = '';
if($argc >= 2)
{
    $p = $argv[1];
    if(substr($p, 0, 1) == '-')
    {
        $p = substr($p,1);
        $result = isset(god_init::$$p) ? god_init::$$p : 'error';
    }
    else
    {
        $result = god_init::$p();
    }

//    godinit::$p();
//'-v'==$argv[1] && $result = godinit::$VERSION;
//'init' == $argv[1] && $result = getConfig(godinit::init());
//'make' == $argv[1] && $result = godinit::make();
}
echo $result . PHP_EOL;
