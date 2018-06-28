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
     * @RequestMapping("/login",Method=GET);
     */
    function userLogin(){
        echo 'login page';
    }
}