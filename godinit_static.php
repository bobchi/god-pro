<?php
class godinit{
    static $v = 'god version is 1.1';
    static $prj_name = '';
    static $prj_author = '';
    static function init(){
        echo 'input your project name?'.PHP_EOL;
        self::$prj_name = fgets(STDIN);
        echo 'input your project author?'.PHP_EOL;
        self::$prj_author = fgets(STDIN);

        return json_encode([
            "name" => self::$prj_name,
            "author" => self::$prj_author
        ]);
    }
    static function make(){
        $pchar = new Phar('god.phar');
        $pchar->buildFromDirectory(dirname(__FILE__));
        $pchar->setStub($pchar->createDefaultStub('god'));
        $pchar->compressFiles(Phar::GZ);
    }
    static function __callStatic($name, $arguments)
    {
        echo 'error function';
    }
}