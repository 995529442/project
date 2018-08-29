<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LarryBlogCMS-Home</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/common/bootstrap/css/bootstrap.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/common/global.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" media="all">
    <script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/series-label.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/oldie.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
</head>
<body>
<section class="larry-wrapper">
    <!-- overview -->
    <div class="row state-overview">
        <div id="container" style="width:80%;height:400px;margin:0 auto;"></div>
    </div>

    <div class="row state-overview">
        <div id="container2" style="width:50%;height:400px;"></div>
    </div>
    <!-- overview end -->
</section>
<script type="text/javascript" src="/assets/common/layui/layui.js"></script>
<script type="text/javascript">
    var categories = '{{$categories}}';
    var categories_arr = new Array();

    var tangshi = '{{$tangshi}}';
    var tangshi_arr = new Array();

    var waimai = '{{$waimai}}';
    var waimai_arr = new Array();

    if(categories != ""){
        categories_arr = categories.split(",");
    }

    if(tangshi != ""){
        tangshi_arr = tangshi.split(",");
        for(var k=0;k<tangshi_arr.length;k++){
            tangshi_arr[k] = parseInt(tangshi_arr[k]);
        }
    }

    if(waimai != ""){
        waimai_arr = waimai.split(",");
        for(var kk=0;kk<waimai_arr.length;kk++){
            waimai_arr[kk] = parseInt(waimai_arr[kk]);
        }

    }

    var chart = Highcharts.chart('container', {
        chart: {
            type: 'line'
        },
        title: {
            text: '最近7天订单趋势统计'
        },
        subtitle: {
            text: '已完成订单数'
        },
        xAxis: {
            categories: categories_arr
        },
        yAxis: {
            title: {
                text: '订单数(/笔)'
            }
        },
        credits:{
            enabled: false // 禁用版权信息
        },
        plotOptions: {
            line: {
                dataLabels: {
                    // 开启数据标签
                    enabled: true
                },
                // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                enableMouseTracking: false
            }
        },
        series: [{
            name: '外卖',
            data: waimai_arr
        }, {
            name: '点餐',
            data: tangshi_arr
        }]
    });
    // Build the chart
    Highcharts.chart('container2', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '支付方式份额情况'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        credits:{
            enabled: false // 禁用版权信息
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: '购物币支付',
                y: {{$currency_count}},
            }, {
                name: '微信支付',
                y:{{$wx_count}}
            }]
        }]
    });
</script>
</body>
</html>