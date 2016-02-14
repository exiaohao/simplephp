<?php
/**
 * Created by PhpStorm.
 * User: songhao
 * Date: 16/2/7
 * Time: 上午1:39
 */

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>注册 - <?=SITE_NAME; ?></title>
    <link rel="stylesheet" href="/css_loader/get/%2Fcss%2Fglobal.css/%2Fcss%2Fheader.css/%2Fbootstrap%2Fcss%2Fbootstrap.css" rel="stylesheet" media="screen">
  </head>
  <body>

<div class="container">
  <div class="row">
    <div class="span12">
      <h2><?=SITE_NAME; ?></h2>
      <h4>考生注册</h4>
      <hr />
    </div>
  </div>
  <div class="row">
    <div class="span6">
      <form id="registerform" action="register/do_register" method="post">
        <h4>账号信息</h4>
        <label for="idcard" class="pull-left">身份证号码</label><span class="pull-right muted">这将成为您的登录用户名</span>
        <input id="idcard" class="input-block-level" type="text" name="idcard" placeholder="身份证号码" length="18" >

        <label for="password" class="pull-left">密码</label><span class="pull-right muted">至少6位</span>
        <input id="password" class="input-block-level" type="password" name="password" placeholder="密码">

        <label for="repassword">确认密码</label>
        <input id="repassword" class="input-block-level" type="password" name="retype_password" placeholder="确认密码">

        <label for="realname" class="pull-left">考生姓名</label>
        <input id="readname" class="input-block-level" type="text" name="realname" placeholder="考生姓名" >
        <hr />
        <h5>个人信息</h5>
        <label for="stuorig">生源地</label>
        <select id="stuorig" class="input-block-level" name="stu_origin" required>
          <option value="null" selected="selected" disabled="disabled">请选择</option>
          <option value="0571">杭州</option>
          <option value="0572">湖州</option>
          <option value="0573">嘉兴</option>
          <option value="0574">宁波</option>
          <option value="0575">绍兴</option>
          <option value="0576">台州</option>
          <option value="0577">温州</option>
          <option value="0578">丽水</option>
          <option value="0579">金华</option>
          <option value="0570">衢州</option>
          <option value="0580">舟山</option>
        </select>
        <label for="school">学校</label>
        <select id="school" class="input-block-level" name="stu_school" required>
          <option disabled="disabled">请先选择生源地</option>
        </select>

        <h5>联系方式</h5>
        <label for="email" class="pull-left">电子邮件地址</label>
        <input id="email" class="input-block-level" type="email" name="email" placeholder="Email">
        <label for="mobilephone" class="pull-left">手机号码</label><span class="pull-right muted">如 13800573500</span>
        <input id="mobilephone" class="input-block-level" type="text" name="mobilephone" placeholder="家长手机号码">
        <label for="homephone" class="pull-left">家庭电话 (非必须)</label><span class="pull-right muted">如 0573-83640000</span>
        <input id="homephone" class="input-block-level" type="text" name="homephone" placeholder="家庭电话">

        <hr />
        <input type="hidden" name="token" value="<?=$argv['uhash']; ?>">
        <button class="btn btn-success btn-large" type="submit" name="button">注册账号</button>
      </form>
    </div>
  </div>
</div>
<script src="static/js/jquery-1.10.2.min.js"></script>
<script src="static/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#registerform').validate({
    rules:{
      password:{
        required:true,
        minlength:6
      },
      retype_password:{
        required:true,
        minlength:6,
        equalTo:'#password'
      },
      stu_origin:{
        required:true
      },
      stu_school:{
        required:true
      },
      realname:{
        required:true,
        minlength:2
      },
      idcard:{
        required:true,
        rangelength:[18,18]
      },
      email:{
        required:true,
        email:true
      },
      mobilephone:{
        required:true,
        rangelength:[11,11]
      }
    },
    messages:{
      password:{
        required:'请输入您的密码',
        minlength:'密码至少6位',
      },
      retype_password:{
        required:'请重新输入您的密码',
        minlength:'密码至少6位',
        equalTo: '两次输入密码不一致不一致'
      },
      stu_origin:{
        required:'请选择您的生源地'
      },
      stu_school:{
        required:'请选择您的学校'
      },
      realname:{
        required:'请输入您的姓名',
        minlength:'姓名至少2个字'
      },
      idcard:{
        required:'请输入您的身份证号码',
        rangelength:'身份证号码为18位，遇X请大写'
      },
      email:{
        required:'请输入您的电子邮箱地址',
        email:'请输入正确的电子邮箱地址'
      },
      mobilephone:{
        required:'请输入您家长的手机号码',
        rangelength:'手机号码为11位'
      }
    }
  });
});
$(function(){
  $('#idcard').change(function(){
    $.ajax({
      url: '/register/check_idcard/'+$(this).val(),
      success: function(data){
        if(data.status === 0)
        {
        }
        else {
          $('#idcard').val(data.msg)
        }
      },
      dataType: 'json'
    });
  })
  $('#stuorig').change(function(){
    $('#homephone').val($(this).find('option:selected').val()+'-');
    $.ajax({
      url: '/register/get_school_detail/'+$(this).find('option:selected').val(),
      success: function(data){
        if(data.status === 0)
        {
          $('#school').html('');
          $(data.result).each(function(i,e){
            $('#school').append('<option value="'+e.id+'">'+e.school+'</option>');
          })
        }
        else {
          alert('请选择城市')
        }
      },
      dataType: 'json'
    });
  })
  $('#school').change('')
})
</script>
