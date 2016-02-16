<?php

/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/7
 * Time: 上午12:58
 */
session_start();
class register extends common
{
    var $ar;
    function __construct($additional_requests)
    {
        parent::__construct();
        $this->ar = $additional_requests;
    }

    function index()
    {
        $uip = $this->utils->user_ip();
        $uhash = md5('user_reg_request_'.$uip.'_'.time());
        $session_key = 'register_session_'.$uhash;
        $this->redis->SET($session_key, $uip);
        $this->redis->EXPIRE($session_key, TTL_REGISTER_ATTEMPT);
        $_SESSION['token'] = $uhash;
        $this->load_page('register', array('uhash'=>$uhash));
    }
    /*
     * 检查是否允许注册
     * TODO:改为检查redis
     */
    function check_register_enbaled()
    {
        $data = $this->mysql->query('SELECT * FROM `system_config` WHERE  `key` LIKE  \'REG_ENABLE_TIME\';');
        $value_data = json_decode($data->fetch_assoc()['value']);
        if(time() > $value_data[0] && time() < $value_data[1])
            return true;
        else
            return false;
    }
    /*
     * 读取学校信息
     * 返回json
     */
    function get_school_detail()
    {
        $telcode = is_numeric($this->ar[0])?$this->ar[0]:$this->global_error->show_entire(405);
        header('Content-Type:application/json; charset=utf-8');
        $sql = "SELECT * FROM  `school_list` WHERE  `telcode` LIKE '{$telcode}' ORDER BY  `school_list`.`postcode` ASC;";
        $items = $this->mysql->query($sql);
        if($items->num_rows > 0)
        {
            $result = array();
            while($row = $items->fetch_array())
            {
                foreach($row as $k=>$v)
                {
                    if(is_numeric($k))  unset($row[$k]);
                }
                $result[] = $row;
            }
            die(json_encode(array('status'=>0, 'msg'=>'ok', 'result'=>$result)));
        }
        else
        {
            die(json_encode(array('status'=>404, 'msg'=>'未找到相关学校')));
        }
    }


    /*
     * 检查用户名(身份证号码)是否被注册
     * 返回json
     */
    function check_idcard()
    {
        header('Content-Type:application/json; charset=utf-8');
        $user_token = $_SESSION['token'];
        if($user_token == '')
        {
            die(json_encode(array('status'=>-1, 'msg'=>'非法用户')));
        }

        if(!$this->utils->request_freq_protect('register_CHK_IDCARD', $user_token, TTL_REGISTER_CHK_IDCARD, FREQ_REGISTER_CHK_IDCARD))
            die(json_encode(array('status'=>-1, 'msg'=>'您的请求过快')));

        $idcard = $this->ar[0];
        if($this->utils->checkIdCard($idcard))
        {
            $sql_check = $this->mysql->query('SELECT  `idcard` FROM `student_basicinfo` WHERE `idcard` LIKE \''.$idcard.'\' LIMIT 1');
            if($sql_check->num_rows == 0)
                die(json_encode(array('status'=>0, 'msg'=>'身份证合法')));
            else
                die(json_encode(array('status'=>-1, 'msg'=>'此身份证号码已经注册过')));
        }
        else
        {
            die(json_encode(array('status'=>-1, 'msg'=>'身份证号码不合法')));
        }
    }
    /*
     * 处理注册SQL
     */
    function register_sql($data_arr)
    {
        $pass_salt = substr(md5(time()), 0, 6);
        $pass_hash = $this->creat_pass_hash($data_arr['password'], $pass_salt);

        $html_text_format = '<h2>%s同学,恭喜你已经成功在嘉兴学院三位一体招生系统注册</h2><p>您的登录账户名为:%s</p><p>您的密码为:%s</p><p>请牢记您的账户和密码<br />如有问题,请联系 0573-83640000 或邮箱 bkzs@admission.zjxu.edu.cn<br />您可以直接回复本邮件获得帮助</p>';
        /*
         * TODO:身份证遇到X大写
         * 校验身份证合法性
         */

        $sql = "INSERT INTO `student_basicinfo`(`idcard`, `password`, `password_salt`, `name`, `stu_origin`,
                 `school_id`, `email`, `mobile`, `homephone`, `status`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');";
        $fsql = sprintf($sql, strtoupper($data_arr['idcard']), $pass_hash, $pass_salt, $data_arr['realname'],
            $data_arr['stu_origin'], $data_arr['stu_school'], $data_arr['email'], $data_arr['mobilephone'],
            $data_arr['homephone'], $data_arr['status']);

        $act = $this->mysql->query($fsql);
        if($act)
        {
            /*
             * 注册成功,发送邮件
             */
            if($this->utils->send_mail($data_arr['email'], $data_arr['realname'], '嘉兴学院三位一体招生系统 - 学生注册成功', sprintf($html_text_format, $data_arr['realname'], $data_arr['idcard'], $data_arr['password'])))
            {
                /*
                 * 发邮件成功,跳转到登录
                 */
            }
            else
            {
                /*
                 * 发邮件失败,记录事件
                 * TODO
                 */
            }
            header('Location:/account#!/type/register_successful');
        }
        else
        {
            /*
             * 注册失败,返回错误
             */
            echo $this->global_error->register_error;
        }
    }

    /*
     * 处理注册请求
     */
    function do_register()
    {
        $user_token = $_POST['token'];
        if($this->redis->TTL('register_session_'.$user_token) > -1)
        {
            $required_fields = array('idcard', 'password', 'retupe_password', 'realname', 'stu_origin', 'stu_school',
                'email', 'mobilephone', 'homephone', 'token');
            foreach($_POST as $pkey=>$pvalue)
            {
                if(in_array($pkey, $required_fields))
                {
                    if(empty($_POST[$pkey]))
                    {
                        $this->global_error->bad_request($this->global_error->filed_not_full);
                        die($pkey);
                    }
                }
            }
            /*
             * 检查配置,是否现在在允许注册时间内
             */
            if($this->check_register_enbaled()) {
                echo 'GO REG SQL';
                $this->register_sql($_POST);
            }
            else
                $this->global_error->bad_request($this->global_error->register_disabled);

        }
        else
        {
            $this->global_error->bad_request($this->global_error->expired);
        }
    }


}