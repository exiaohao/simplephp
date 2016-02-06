<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/6
 * Time: 下午9:50
 */
class test extends common
{
    function index()
    {
        echo 'im index';
    }

    function action()
    {
        $this->load_page('test_action');
    }
}