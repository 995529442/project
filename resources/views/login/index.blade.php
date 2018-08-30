<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
    <link href="/assets/css/login1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="login_box">
    <div class="login_l_img"><img src="/assets/images/login-img.png" /></div>
    <div class="login">
        <div class="login_logo"><img src="/assets/images/login_logo.png" /></div>
        <div class="login_name">
            <p>后台管理系统</p>
        </div>
        <form action="{{ route('login.checkLogin') }}" method="post" onsubmit="return check_login();">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="用户名" />
            <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="密码" />
            <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="验证码" maxlength="4" style="width:52%;"/>
            <img src="{{ route('login.captcha') }}" alt="" class="verifyImg" id="verifyImg"
                 onClick="javascript:this.src='{{ route('login.captcha') }}?r='+Math.random();" style="width:45%;height:48px;margin-top:-18px;">
            <input value="立即登陆" style="width:100%;" type="submit">
        </form>
    </div>
    <div class="copyright">@2018 牛叔叔技术分享 粤ICP备18107654号-1 & 版权所有</div>
</div>
<div style="text-align:center;display:none">
    <p>更多模板：<a href="http://www.aspku.com/" target="_blank">网站源码库</a></p>
</div>
</body>
<script type="text/javascript" src="/assets/common/layui/lay/dest/layui.all.js"></script>
<script type="text/javascript" src="/assets/js/login.js"></script>
<script type="text/javascript" src="/assets/jsplug/jparticle.jquery.js"></script>
<script>
    //登录验证
    function check_login() {
        var username = $("#username").val();
        var password = $("#password").val();
        var code = $("#code").val();

        if (username == "" || username == null) {
            layer.msg('用户名不能为空', {icon: 2}, 1500);
            return false;
        }

        if (password == "" || password == null) {
            layer.msg('密码不能为空', {icon: 2}, 1500);
            return false;
        }

        if (code == "" || code == null) {
            layer.msg('验证码不能为空', {icon: 2}, 1500);
            return false;
        }
    }
</script>
</html>
