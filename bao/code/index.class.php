<?php
/**
 * this is my index
 * @Controller
 */
class index
{
    /**
     *  *@RequestMapping("/getme",Method=GET);
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