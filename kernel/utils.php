<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/7
 * Time: 上午1:16
 */
class utils extends db
{
    /*
     * Creat UUID
     */
    function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }
    /*
     * 获取客户端IP
     */
    var $Mail;
    function __construct()
    {
        //$this->Mail = new Mail;
        parent::__construct();
    }

    function user_ip()
    {
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    /*
     * 发送邮件
     *
     */
    function send_mail($to, $to_name, $subject, $text)
    {
        if($to_name == '')  $to_name = $to;
        if(!isset($to) || empty($subject) || empty($text))
        {
            return false;
        }
        require_once('utils/class.phpmailer.php');
        require_once("utils/class.smtp.php");
        $mail  = new PHPMailer();

        $mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->IsSMTP();                            // 设定使用SMTP服务
        $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl";                  // SMTP 安全协议
        $mail->Host       = "smtp.exmail.qq.com";       // SMTP 服务器
        $mail->Port       = 465;                    // SMTP服务器的端口号
        $mail->Username   = "no-reply@admission.zjxu.edu.cn";  // SMTP服务器用户名
        $mail->Password   = "3f!o2V3zBfC5O@0";        // SMTP服务器密码
        $mail->SetFrom('no-reply@admission.zjxu.edu.cn', '嘉兴学院招生网');    // 设置发件人地址和名称
        $mail->AddReplyTo("bkzs@admission.zjxu.edu.cn","嘉兴学院招生网");
        // 设置邮件回复人地址和名称
        $mail->Subject    = $subject;                     // 设置邮件标题
        $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
        // 可选项，向下兼容考虑
        $mail->MsgHTML($text);                         // 设置邮件内容
        $mail->AddAddress($to, $to_name);
        if(!$mail->Send()) {
            /*
             * TODO:加入错误日志
             */
            return false;
        } else
            return true;

        return false;
    }

    /*
     * 校验身份证号码
     *
     */
    function checkIdCard($idcard){
        // 只能是18位
        if(strlen($idcard)!=18){
            return false;
        }
        $idcard = strtoupper($idcard);
        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);
        // 取出校验码
        $verify_code = substr($idcard, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for($i=0; $i<17; $i++){
            $total += substr($idcard_base, $i, 1)*$factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        if($verify_code == $verify_code_list[$mod]){
            return true;
        }else{
            return false;
        }

    }

    /*
     * 请求次数保护
     * 请求名,token,超时(sec),超时内最大次数
     */
    function request_freq_protect($name, $token, $ttl, $freq_count)
    {
        if(!isset($name) || !isset($token))
            return false;
        else
        {
            $redis_key = $name.'_'.$token;
            $this->redis->incr($redis_key);
            $this->redis->EXPIRE($name.'_'.$token, $ttl);

            $count = $this->redis->get($redis_key);
            if($count > $freq_count)
                return false;
            else
                return true;
        }

    }
}