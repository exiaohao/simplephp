<?php
/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/6
 * Time: 下午8:42
 */
class global_error
{
    var $footer = '<address>ETNWS WebSlim Server</address>';

    function show_entire($error_code = 1)
    {
        /*
         *
         */
        $error = array(
            '404'=>array(
                'header'=>'HTTP/1.0 404 Not Found',
                'content'=>'<h1>404 Not Found</h1><hr /><p>There\'s no file '.$_SERVER['REQUEST_URI'].' from this  server.</p>'
            ),
            '403'=>array(
                'header'=>'HTTP/1.1 403 Forbidden',
                'content'=>'<h1>403 Forbidden</h1><hr /><p>You do not have permission to get URL  from this server.</p>'
            )
        );

        if(isset($error[$error_code]))
        {
            header($error[$error_code]['header']);
            die($error[$error_code]['content']);
        }
        else
        {
            die('error occured!');
        }
    }
}