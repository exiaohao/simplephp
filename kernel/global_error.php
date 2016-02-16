<?php
/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/6
 * Time: 下午8:42
 */
class global_error
{
    var $footer = '<br /><address>ETNWS WebSlim Server</address>';
    var $expired = '<p>您的请求已经过期,请返回页面并刷新</p><p><a class="btn btn-info" href="javascript:history.back()">返回</a></p>';
    var $filed_not_full = '<p>您的表单有未填的项目</p><p><a class="btn btn-info" href="javascript:history.back()">返回</a></p>';
    var $register_error = '<p>注册过程中发生错误,如有问题请联系管理员</p>';
    var $register_disabled = '<h3>招生尚未开始/已经结束</h3><p>当前不允许注册新用户</p>';
    var $bad_login_attempt = '<h3>错误的登录请求</h3><p>系统收到了非法的登录请求,如果您是正常操作,请<a class="btn btn-info" href="/account">返回</a>重试</p>';
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
                'content'=>'<h1>403 Forbidden</h1><hr /><p>You do not have permission to get URL '.$_SERVER['REQUEST_URI'].' from this server.</p>'
            ),
            '405'=>array(
                'header'=>'HTTP/1.1 405 Method not allowed',
                'content'=>'<h1>405 Method not allowed</h1><hr /><p>You don\'t have correct way to get '.$_SERVER['REQUEST_URI'].' from this server.</p>'
            ),
            '406'=>array(
                'header'=>'HTTP/1.1 406 Not Acceptable Response',
                'content'=>'<h1>406 Not Acceptable Response</h1><hr /><p>You don\'t have a correct way to get '.$_SERVER['REQUEST_URI'].' from this server.</p>'
            )
        );

        if(isset($error[$error_code]))
        {
            header($error[$error_code]['header']);
            die($error[$error_code]['content'].$this->footer);
        }
        else
        {
            die('error occured!');
        }
    }

    function bad_request($type)
    {
        echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=utf-8"><title>发生了一点错误</title><link rel="stylesheet" href="/css_loader/get/%2Fcss%2Fpage_not_found.css" media="screen"></head><body><div class="container"><h1>发生了一点错误</h1><p><strong>'.$type.'</strong></p><div id="suggestions"><a href="http://www.zjxu.edu.cn">嘉兴学院</a><a href="http://admission.zjxu.edu.cn">招生网</a><a href="http://xgb.zjxu.edu.cn">学生工作部(处)</a></div></div></body></html>';
    }
}