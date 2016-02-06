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


require 'class_db.php';

class common extends db
{
    var $global_error;
    function __construct()
    {
        parent::__construct();
        $this->global_error = $this->callClass('global_error');
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
    function load_page($page)
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
}