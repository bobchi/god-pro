<?php
require 'vars';
require 'functions';

function getMatch($k){
    return preg_match('/[a-zA-Z]+/', $k);
}

$display = function($tpl = ''){
    require 'vars';
    if($tpl!='') include 'page/'.$tpl.'.html';
};

$path = isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : false;
!$path && exit('404');

$route = require 'request_route';

//!array_key_exists($path,$route) && exit('path is error');
$routeKeys = array_keys($route);

foreach ($routeKeys as $routeKey)
{
//    var_dump($routeKey);
    $newKey = str_replace('/','\/',$routeKey);
//    print_r('/'.$newKey.'/');exit();
    if(preg_match('/'.$newKey.'/',$path,$pregRes)){
        $routeObj = $route[$routeKey];

        if($routeObj['RequestMethod'] == $_SERVER['REQUEST_METHOD'])
        {
            $className = $routeObj['Class'];
            $classMethod = $routeObj['Method'];
//            var_dump($classMethod);exit;
            require('code/'.$className.'.class.php');

            $params = array_filter($pregRes, 'getMatch', ARRAY_FILTER_USE_KEY);
//            var_export($params);exit;

            $classObj = new ReflectionClass($className);
            $getMethod = $classObj->getMethod($classMethod);

            $params['display'] = $display;
//            if($params && count($params)>0){
                $getMethod->invokeArgs($classObj->newInstance(), $params);
//            }else{
//                $getMethod->invoke($classObj->newInstance());
//            }
//            (new $className())->$classMethod();
//            exit();
        }
        else {
            exit('method not allowed');
        }
    }
}



//var_export($route);