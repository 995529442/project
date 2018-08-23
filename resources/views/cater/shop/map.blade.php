<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微餐饮</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-sshopcale=1">
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

    #container {
        width: 700px;
        height: 400px;
    }
</style>
<body onload="init()">
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <div class="layui-form-item">
        <label class="layui-form-label">详细地址：</label>
        <div class="layui-input-block">
            <input type="text" name="address" id="address" autocomplete="off" class="layui-input"
                   value="{{$detail_address}}" style="display:inline-block;width:80%;">
            <button style="display:inline-block;margin-left:20px;margin-top:-5px;" onclick="codeAddress();"
                    class="layui-btn">搜索
            </button>
        </div>
    </div>
</form>
<!--   定义地图显示容器   -->
<div id="container"></div>
<div style="width:603px;" id="latLng">纬度：{{$latitude}},经度：{{$longitude}}</div>
<div class="layui-form-item" style="margin-top:10px;margin-bottom:10px;">
    <label class="layui-form-label">经度：</label>
    <div class="layui-input-inline">
        <input type="text" name="longitude" id="longitude" autocomplete="off" class="layui-input"
               value="{{$longitude}}">
    </div>
    <label class="layui-form-label">纬度：</label>
    <div class="layui-input-inline">
        <input type="text" name="latitude" id="latitude" autocomplete="off" class="layui-input" value="{{$latitude}}">
    </div>
    <button style="display:inline-block;" onclick="check_address();" class="layui-btn">确认</button>
</div>
</body>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
    function init() {
        var myLatlng = new qq.maps.LatLng({{$latitude}}, {{$longitude}});
        var myOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: qq.maps.MapTypeId.ROADMA
        }
        var map = new qq.maps.Map(document.getElementById("container"), myOptions);

        //调用地址解析类
        geocoder = new qq.maps.Geocoder({
            complete: function (result) {
                map.setCenter(result.detail.location);
                var marker = new qq.maps.Marker({
                    map: map,
                    position: result.detail.location
                });
            }
        });
        //添加监听事件
        qq.maps.event.addListener(
            map,
            'click',
            function (event) {
                document.getElementById("latitude").value = event.latLng.getLat();
                document.getElementById("longitude").value = event.latLng.getLng();
            }
        );

        qq.maps.event.addListener(map, 'mousemove', function (event) {
            var latLng = event.latLng,
                lat = latLng.getLat(),
                lng = latLng.getLng();
            document.getElementById("latLng").innerHTML = '纬度：' + lat + ',经度：' + lng;
        });
    }

    function codeAddress() {
        var address = document.getElementById("address").value;
        //通过getLocation();方法获取位置信息值
        geocoder.getLocation(address);
    }

    function check_address() {
        var longitude = document.getElementById("longitude").value;
        var latitude = document.getElementById("latitude").value;

        if (longitude == "" || latitude == "") {
            layer.msg('请先在地图上标志地址', {icon: 2}, 1500);
            return;
        }

        window.parent.document.getElementById("longitude").value = longitude;
        window.parent.document.getElementById("latitude").value = latitude;

        parent.layer.closeAll();
    }
</script>
</html>
