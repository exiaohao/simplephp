<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>考生面板 - <?=SITE_NAME; ?></title>
     <link rel="stylesheet" href="/css_loader/get/%2Fbootstrap%2Fcss%2Fbootstrap.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="span12">
          <h3><?=SITE_NAME; ?></h3>
          <hr />
        </div>
      </div>
      <div class="row">
        <div class="span3">
          <ul id="navi" class="nav nav-tabs nav-stacked">
            <li><a href="#!/home">考生首页</a></li>
            <li><a href="#!/personal_info">个人信息</a></li>
            <li><a href="#!/huikao_grade">学业资料</a></li>
            <li><a href="#!/school_info">学校信息</a></li>
            <li><a href="#!/apply_form">报名表</a></li>
            <li><a href="#!/mail_materials">邮寄资料</a></li>
            <li><a href="#!/print_cert">打印准考证</a></li>
            <li><a href="#!/results">录取结果</a></li>
          </ul>
        </div>
        <div class="span9">
          bbb
        </div>
      </div>
    </div>

<script src="static/js/jquery-1.10.2.min.js"></script>
<script src="static/bootstrap/js/bootstrap.min.js"></script>
<script>
function getUrlParam(num)
{
	var r = window.location.hash.substr(1).split('/');
	if (r!=null) return unescape(r[num]); return null;
}
$(function(){
  $('#navi li a').click(function(){
    var request_page = getUrlParam(1)
    if(request_page === undefined || request_page === 'home')
    {
      console.log('home')
    }
  })
})


</script>
