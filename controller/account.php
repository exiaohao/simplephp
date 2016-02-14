<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/7
 * Time: 上午12:58
 */
class account extends common
{
    var $ar;
    var $utils;
    function __construct($additional_request)
    {
        parent::__construct();
        $this->ar = $additional_request;
    }
    function index()
    {
        $uip = $this->utils->user_ip();
        $uhash = md5('user_login_request_'.$uip.'_'.time());
        $this->redis->SET($uhash, $uip);
        $this->redis->EXPIRE($uhash, TTL_LOGIN_ATTEMPT);
        $this->load_page('login', array('uhash'=>$uhash, 'uip'=>$uip));
    }

    function login_attempt()
    {
        print_r($_POST);
    }

}