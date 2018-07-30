<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台登录</title>
    <meta name="renderer" content="webkit"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <meta name="format-detection" content="telephone=no">   
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/css/login.css" media="all">
</head>
<body>
<div class="layui-canvs"></div>
<div class="layui-layout layui-layout-login">
    <h1>
         <strong>后台管理系统</strong>
         <em>Management System</em>
    </h1>
    <form action="{{ route('login.checkLogin') }}" method="post" onsubmit="return check_login();">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-user-icon larry-login">
             <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="用户名" class="login_txtbx"/>
        </div>
        <div class="layui-pwd-icon larry-login">
             <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="密码" class="login_txtbx"/>
        </div>
        <div class="layui-val-icon larry-login">
                <input type="text" id="code" value="{{ old('code') }}" name="code" placeholder="验证码" maxlength="4" class="login_txtbx" style="width:52%;">
                <img src="{{ route('login.captcha') }}" alt="" class="verifyImg" id="verifyImg" onClick="javascript:this.src='{{ route('login.captcha') }}?r='+Math.random();">
        </div> 
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul style="color:red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif       
        <div class="layui-submit larry-login">
            <input type="submit" value="立即登陆" class="submit_btn"/>
        </div>
    </form>
    <div class="layui-login-text">
        <p>© 2007-2018 牛叔叔 版权所有</p>
        <p>粤1088-1044-1123</p>
    </div>
</div>
<script type="text/javascript" src="/assets/common/layui/lay/dest/layui.all.js"></script>
<script type="text/javascript" src="/assets/js/login.js"></script>
<script type="text/javascript" src="/assets/jsplug/jparticle.jquery.js"></script>
<script type="text/javascript">
    $(function(){
        $(".layui-canvs").jParticle({
            background: "#141414",
            color: "#E6E6E6"
        });
    });
    
    //登录验证
    function check_login(){
        var username = $("#username").val();
        var password = $("#password").val();
        var code     = $("#code").val();

        if(username == "" || username == null){
            layer.msg('用户名不能为空', {icon: 2},1500);
            return false; 
        }

        if(password == "" || password == null){
            layer.msg('密码不能为空', {icon: 2},1500);
            return false; 
        }

        if(code == "" || code == null){
            layer.msg('验证码不能为空', {icon: 2},1500);
            return false; 
        }
    }
</script>
</body>
</html>