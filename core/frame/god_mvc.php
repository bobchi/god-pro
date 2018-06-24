<?php
namespace core\frame;
class god_mvc
{
    public $className = '';
    public $classComment = '';
    public $classMethods = array();
    function __construct($className){
        $this->className = $className;
        $f = new \ReflectionClass($this->className);
        $this->classComment = $f->getDocComment();
        $this->classMethods = $f->getMethods(); // get all methods in this class
    }

    function isController(){
        return preg_match("/@Controller/",$this->classComment);
    }

    function getRequestMapping(){
        $resArr = [];
        foreach ($this->classMethods as $method){
            $res = $this->genRequestMappingResult($method);
            if($res){
                $resArr = array_merge($resArr, $res);
            }
        }
        return $resArr;
    }

    function genRequestMappingResult($method){
        // @RequestMapping("/getme",Method=GET);
        if(preg_match('/@RequestMapping\("(?<RequestUrl>.{3,50})",Method=(?<RequestMethod>\w{3,8})\)/',$method->getDocComment(),$result)){

//            var_export($result);
            return [
                $result['RequestUrl'] => [
                    'RequestMethod' => $result['RequestMethod'],
                    'Class' => $this->className,
                    'Method' => $method->getName()
                ]
            ];
        }
        return false;
    }
}