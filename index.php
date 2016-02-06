<?php
define('IN_PAGE', 1);
require 'kernel/common.php';
require 'kernel/global_error.php';
if(DEBUG)
    error_reporting(E_ALL&~E_NOTICE);
else
    error_reporting(0);

$common = new common();
/*
 * Global Error
 */
$global_error = new global_error;

/*
 * Route
 */
$request_uri = trim($_SERVER["REQUEST_URI"]);
while(substr($request_uri, 0, 1) == '/')
{
    $request_uri = substr($request_uri, 1);
}
// 默认页面
if(empty($request_uri))
{
    echo 'default page';
}
else
{
    if (strpos($request_uri, '?') > strpos($request_uri, ';')) {
        $method_array = explode(';', $request_uri);
    } else {
        $method_array = explode('?', $request_uri);
    }
    $request_method = explode('/', $method_array[0]);

    $controller_path = __DIR__ . '/controller/' . $request_method[0] . '.php';
    $fp = fopen($controller_path, 'r');
    if ($fp) {
        require $controller_path;
        $controller = new $request_method[0];
        if ($request_method[1] == '') {
            $controller->index();
        } else {
            $controller->$request_method[1]();
        }
    } else {
        $global_error->show_entire(404);
    }
}
?>