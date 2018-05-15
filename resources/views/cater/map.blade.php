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
<style type="text/css">
#container{
   width:1000px;
   height:450px;
}
</style>
<body>	
<form class="layui-form" action="" method="post">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐厅名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="address" autocomplete="off" class="layui-input" value="{{$detail_address}}" style="display:inline-block;width:40%;">
	      <button style="display:inline-block;margin-left:20px;margin-top:-5px;" class="layui-btn">提交</button>
	    </div>
    </div>     
</form>
<!--   定义地图显示容器   -->
<div id="container"></div>
</body>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>

window.onload = function(){
    //初始化地图函数  自定义函数名init
    function init() {
        //定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
         var map = new qq.maps.Map(document.getElementById("container"), {
            center: new qq.maps.LatLng({{$latitude}},{{$longitude}}),      // 地图的中心地理坐标。
            zoom:8                                                 // 地图的中心地理坐标。
        });
    }
    init();
}
</script>
</html>
