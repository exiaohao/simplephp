<?php
session_start();
/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/15
 * Time: 下午7:46
 */
class my extends common
{
    var $ar;
    function __construct($additional_request)
    {
        parent::__construct();
        $this->ar = $additional_request;
    }

    /*
     * 考生面板主页
     */
    function index()
    {
        //print_r($_SESSION);
        $this->load_page('my_panel');
    }
}