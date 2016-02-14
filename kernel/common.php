<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/6
 * Time: 下午8:51
 */

define('DEBUG', true);
//DEFAULT_HOMEPAGE
define('DEFAULT_HOMEPAGE', 'welcome');
//SITE_NAME
define('SITE_NAME', '嘉兴学院三位一体招生');
//TTL_LOGINATTEMPT
define('TTL_LOGIN_ATTEMPT', 90);
//TTL_REGISTER_ATTEMPT
define('TTL_REGISTER_ATTEMPT', 1200);
//TTL_REGISTER_CHECK_IDCARD_FREQUENCY
define('TTL_REGISTER_CHK_IDCARD', 15);
//FREQ_REGISTER_CHK_IDCARD
define('FREQ_REGISTER_CHK_IDCARD', 4);

require 'class_db.php';
require 'utils.php';

class common extends db
{
    var $global_error;
    var $utils;
    function __construct()
    {
        parent::__construct();
        $this->global_error = $this->callClass('global_error');
        $this->utils = $this->callClass('utils');
    }
    /*
     * Call Class
     */
    function callClass($class_to_call)
    {
        return new $class_to_call();
    }
    /*
     * Load Page From '/view/{$page}.php'
     */
    function load_page($page, $argv = array())
    {
        if(empty($page))
        {
            $this->global_error->show_entire(404);
        }
        else {
            $page_path = __DIR__ . "/../view/{$page}.php";
            $fp = fopen($page_path, 'r');
            if ($fp) {
                require $page_path;
            } else {
                $this->global_error->show_entire(404);
            }
        }
    }
    /*
     * 创建密码hash
     * 方法 md5(password+salt)
     */
    function creat_pass_hash($pass, $salt = '')
    {
        return md5($pass.$salt);
    }
    /*
     * 检查用户的密码
     */
    function check_pass_hash($account_name, $password)
    {

    }

    /*
     *发送邮件
     */
}