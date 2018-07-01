<?php
/**
 * this is my index
 * @Controller
 */
class index
{
    /**
     * @RequestMapping("/getme/(?<name>\w{2,10})/(?<age>\d+)$",Method=GET);
     */
    function defaults($name,$age){
        echo 'hello, '. $name.'<hr>'.'my age is '.$age;
//        showAge();
    }

    /**
     * @RequestMapping("/login$",Method=GET);
     */
    function userLogin($display){
        $vars['name'] = 'yang qiu xia';
        $vars['web'] = 'qj4.cn';
        $display('login', $vars);
//        echo 'login page';
    }

    /**
     * @RequestMapping("/login_post$",Method=POST);
     */
    function userLoginPost($uname,$display){
        $obj = new stdClass();
        $obj->uname = $uname;
        exit(json_encode($obj));
//        echo file_get_contents("php://input");
//        echo 'post';
    }


}