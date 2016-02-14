<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/7
 * Time: 上午12:28
 */
class css_loader extends common
{
    var $ar;
    function __construct($additional_requests)
    {
        parent::__construct();
        $this->ar = $additional_requests;
    }

    function index()
    {

    }
    function get()
    {
        header('Content-Type:text/css');
        foreach($this->ar as $item)
        {
            $file = explode('.', urldecode($item));
            if($file[count($file)-1] == 'css')
            {
                if(strstr($item, '..')) die('Bad Request!');
                @ require __DIR__.'/../static/'.urldecode($item);
            }
            else
            {
                die('Bad Request!');
            }
        }
    }
}