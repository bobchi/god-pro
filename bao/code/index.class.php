<?php
/**
 * this is my index
 * @Controller
 */
class index
{
    /**
     * @RequestMapping("/getme/\w{2,10}",Method=GET);
     */
    function defaults(){
        echo 'hello bao';
        showAge();
    }

    /**
     * @RequestMapping("/getAge",Method=POST);
     */
    function abc(){
        echo 'abc';
    }
}