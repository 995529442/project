<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>后台管理系统</title>
    <link rel="stylesheet" href="/assets/common/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">
    <link rel="stylesheet" href="/js/build/css/app.css" media="all">
</head>

<body>
<div class="layui-layout layui-layout-admin kit-layout-admin">
    <div class="layui-header" style="background-color: #393D49">
        <div class="layui-logo">后台管理系统</div>
        <!--             <div class="layui-logo kit-logo-mobile">K</div>
                    <ul class="layui-nav layui-layout-left kit-nav">
                        <li class="layui-nav-item"><a href="javascript:;">控制台3</a></li>
                        <li class="layui-nav-item"><a href="javascript:;">商品管理</a></li>
                        <li class="layui-nav-item"><a href="javascript:;" id="pay"><i class="fa fa-gratipay" aria-hidden="true"></i> 捐赠我</a></li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">其它系统</a>
                            <dl class="layui-nav-child">
                                <dd><a href="javascript:;">邮件管理</a></dd>
                                <dd><a href="javascript:;">消息管理</a></dd>
                                <dd><a href="javascript:;">授权管理</a></dd>
                            </dl>
                        </li>
                    </ul> -->
        <ul class="layui-nav layui-layout-right kit-nav">
            <li class="layui-nav-item">
                管理员：{{$username}}
            </li>
            <li class="layui-nav-item">
                <a href="{{ route('login.logout') }}">
                    <i class="iconfont icon-exit"></i>
                    退出</a>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black kit-side">
        <div class="layui-side-scroll">
            <div class="kit-side-fold"><i class="fa fa-navicon" aria-hidden="true"></i></div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><span> 后台管理</span></a>
                    <dl class="layui-nav-child">
                        @if($type == 1)
                            <dd>
                                <a href="javascript:;" kit-target
                                   data-options="{url:'{{ route('manage') }}',icon:'',title:'管理员管理',id:'1'}">
                                    <span> 管理员管理</span></a>
                            </dd>
                        @endif
                        <dd>
                            <a href="javascript:;" data-url="{{ route('mail') }}" data-icon="" data-title="邮件设置"
                               kit-target data-id='2'><span> 邮件设置</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" data-url="{{ route('sms') }}" data-icon="" data-title="短信设置"
                               kit-target data-id='3'><span> 短信设置</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" data-url="{{ route('smsTemplate') }}" data-icon="" data-title="短信模板"
                               kit-target data-id='4'><span> 短信模板</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('sendLog') }}',icon:'',title:'发送记录',id:'5'}"><span> 发送记录</span></a>
                        </dd>
                    </dl>
                </li>
                <!-- 微餐饮 -->
                <?php if((in_array("cater", $module_arr) && $type == 2) || $type == 1){ ?>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span> 微餐饮</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.shop.index') }}',icon:'',title:'餐厅管理',id:'6'}"><span> 餐厅管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.home.index') }}',icon:'',title:'首页管理',id:'7'}"><span> 首页管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.template.index') }}',icon:'',title:'模板管理',id:'8'}"><span> 消息模板</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.category.index') }}',icon:'',title:'分类管理',id:'9'}"><span> 分类管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.goods.index') }}',icon:'',title:'商品管理',id:'10'}"><span> 商品管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.desk.index') }}',icon:'',title:'餐桌管理',id:'11'}"><span>餐桌管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.orders.index') }}',icon:'',title:'订单管理',id:'12'}"><span> 订单管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.users.index') }}',icon:'',title:'用户管理',id:'13'}"><span> 用户管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.system.index') }}',icon:'',title:'小程序管理',id:'14'}"><span> 小程序管理</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target
                               data-options="{url:'{{ route('cater.statistics.index') }}',icon:'',title:'统计管理',id:'15'}"><span> 统计管理</span></a>
                        </dd>
                    </dl>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="layui-body" id="container">
         <!-- 内容主体区域 -->
        <div style="padding: 15px;">主体内容加载中,请稍等...</div>
    </div>

    <div class="layui-footer">
        @2018
        <a href="javascript::void(0);">
            牛叔叔技术分享 粤ICP备18107654号-1 & 版权所有
        </a>
    </div>
</div>
<script type="text/javascript">
    // var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    //document.write(unescape("%3Cspan id='cnzz_stat_icon_1264021086'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1264021086%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/common/layui/layui.js"></script>
<script>
    var message;
    layui.config({
        base: 'js/build/js/'
    }).use(['app', 'message'], function () {
        var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
        //将message设置为全局以便子页面调用
        message = layui.message;
        //主入口
        app.set({
            type: 'iframe'
        }).init();

    });

    //轮询获取订单
/*    window.setInterval(function () {
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('getOrders') }}",
            dataType: "json",
            success: function (data) {
            }
        });
    }, 1000 * 60);*/
</script>
</body>

</html>