<?php
session_start();
/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/6
 * Time: 下午11:02
 */
class welcome extends common
{
    function index()
    {
        $user_info = $this->is_loggedin();
        if(!$user_info)
            $this->load_page('welcome');
        else
        {
            $this->load_page('welcome', array('username' =>$user_info['name']));
        }
    }
}