<?php
namespace core;
use core\frame\god_frame;
function __autoload($className){
    $className = str_replace('\\', '/', $className) . '.php';
    require($className);
}
define('FORMAT','JSON');
class god_init{
    static $v = 'god version is 1.1';

    static function init(){
        echo 'input your project name?'.PHP_EOL;
        $prj_name = fgets(STDIN);
        echo 'input your project author?'.PHP_EOL;
        $prj_author = fgets(STDIN);

        echo getConfig(tc(['prj_name'=>$prj_name,'prj_author'=>$prj_author]));
    }

    static function ini(){
        $config = loadConfig();
        foreach ($config as $k=>$v){
            echo $k . ':' . $v;
        }
    }

    static function start(){
        $config = loadConfig();
        (new god_frame($config->prj_name))->run();
    }
}