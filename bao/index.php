<?php
require 'vars';
require 'functions';

$path = isset($_SERVER["PATH_INFO"]) ? isset($_SERVER["PATH_INFO"]) : false;
!$path && exit('404');

$route = require 'request_route';

//!array_key_exists($path,$route) && exit('path is error');
$routeKeys = array_keys($route);

foreach ($routeKeys as $routeKey)
{
//    var_dump($routeKey);
    $newKey = str_replace('/','\/',$routeKey);
//    var_dump($newKey);exit();
    if(preg_match('/'.$newKey.'/',$path)){
        $routeObj = $route[$path];
        if($routeObj['RequestMethod'] == $_SERVER['REQUEST_METHOD'])
        {
            $className = $routeObj['Class'];
            $classMethod = $routeObj['Method'];
//            var_dump($classMethod);exit;
            require('code/'.$className.'.class.php');
            (new $className())->$classMethod();
            exit();
        }
        else {
            exit('method not allowed');
        }
    }
}




//var_export($route);