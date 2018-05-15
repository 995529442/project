<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微餐饮</title>
    <meta name="renderer" content="webkit"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">   
    <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/common/bootstrap/css/bootstrap.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/common/global.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/css/personal.css" media="all">
</head>
<body>
<section class="layui-larry-box">
   @yield('content')
</section>
</body>
</html>