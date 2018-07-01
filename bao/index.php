<?php
require 'vars';
require 'functions';

function getMatch($k){
    return preg_match('/[a-zA-Z]+/', $k);
}

$display = function($tpl = '', $vars = []){
    require 'vars';
    extract($vars);
    if($tpl!='') include 'page/'.$tpl.'.html';
};

function existParam($method,$param){
    foreach ($method->getParameters() as $parameter){
        if($parameter->name == $param) return true;
    }
    return false;
}

function poster($requestMethod, &$params, $method){
    if($requestMethod == 'POST')
    {
        if($_SERVER['CONTENT_TYPE'] == 'application/json'){
            $getObj = json_decode(file_get_contents('php://input'));
            foreach ($getObj as $k=>$v){
                if(existParam($method,$k))
                    $params[$k] = $v;
            }
            return;
        }
        foreach ($_POST as $key=>$value){
            if(existParam($method,$key))
            $params[$key] = $value;
        }
    }
}

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

            poster($_SERVER['REQUEST_METHOD'],$params, $getMethod);

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