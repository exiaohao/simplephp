<?php
session_start();
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
//TTL_LOGIN_USER
define('TTL_LOGIN_USER', 3600);



define('USER_IS_LOGIN', 1);

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
    function check_pass_hash($pass_origin, $passhash, $salt)
    {
        if( md5($pass_origin.$salt) == $passhash ) return true;
        else return false;
    }

    /*
     * 检查用户已经登录?
     * 登录了返回用户信息
     */
    function is_loggedin()
    {
        if ($_SESSION['valid'] == USER_IS_LOGIN)
        {
            if($this->redis->TTL($_SESSION['user_token']) > 0 && $_SESSION['user_id'] > 0)
            {
                $user_info = $this->mysql->query('SELECT * FROM `student_basicinfo` WHERE  `id` = '.$_SESSION['user_id'].' LIMIT 0,1;');
                if($user_info->num_rows > 0)
                    return $user_info->fetch_assoc();
                else {
                    unset($_SESSION['valid']);
                    return false;
                }
            }
            else {
                unset($_SESSION['valid']);
                return false;
            }
        }
    }
}