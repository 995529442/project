<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理系统</title>
    <meta name="renderer" content="webkit"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">   
    <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <meta name="format-detection" content="telephone=no">   
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/common/global.css" media="all">
    <link rel="stylesheet" type="text/css" href="/assets/css/adminstyle.css" media="all">
    
    
</head>
<body>
<div class="layui-layout layui-layout-admin" id="layui_layout">
    <!-- 顶部区域 -->
    <div class="layui-header header header-demo">
        <div class="layui-main">
            <!-- logo区域 -->
            <div class="admin-logo-box">
                <a class="logo" href="{{ route('index') }}" title="logo">后台管理系统</a>
                <div class="larry-side-menu">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
            </div>
            <!-- 右侧导航 -->
            <ul class="layui-nav larry-header-item">
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
    </div>
    <!-- 左侧侧边导航开始 -->
    <div class="layui-side layui-side-bg layui-larry-side" id="larry-side"  style="margin-top:-12px;">
        <div class="layui-side-scroll" id="larry-nav-side" lay-filter="side">
        
        <!-- 左侧菜单 -->
        <ul class="layui-nav layui-nav-tree">
            <li class="layui-nav-item layui-this">
                <a href="javascript:;" data-url="{{ route('main') }}">
                    <i class="iconfont icon-home1" data-icon='icon-home1'></i>
                    <span>后台首页</span>
                </a>
            </li>
            <!-- 后台管理 -->
            @if($type == 1)
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <i class="iconfont icon-caidan2" ></i>
                        <span>后台管理</span>
                        <em class="layui-nav-more"></em>
                    </a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" data-url="{{ route('manage') }}">
                                <i class="iconfont icon-yonghu1" data-icon='icon-geren1'></i>
                                <span>管理员管理</span>
                            </a>                   
                        </dd>
                    </dl>
                </li> 
            @endif
            <!-- 微餐饮 -->
            <?php if((in_array("cater",$module_arr) && $type == 2) || $type == 1){ ?>
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <i class="iconfont icon-caidan2" ></i>
                    <span>微餐饮</span>
                    <em class="layui-nav-more"></em>
                </a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.shop.index') }}">
                            <i class="iconfont icon-zhandianguanli" data-icon='icon-geren1'></i>
                            <span>餐厅管理</span>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.category.index') }}">
                            <i class="iconfont icon-caidan" data-icon='icon-iconfuzhi01'></i>
                            <span>分类管理</span>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.goods.index') }}">
                            <i class="iconfont icon-caidan1" data-icon='icon-piliangicon'></i>
                            <span>商品管理</span>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.orders.index') }}">
                            <i class="iconfont icon-caidan1" data-icon='icon-piliangicon'></i>
                            <span>订单管理</span>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.users.index') }}">
                            <i class="iconfont icon-yonghu1" data-icon='icon-piliangicon'></i>
                            <span>用户管理</span>
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="{{ route('cater.system.index') }}">
                            <i class="iconfont icon-weixin" data-icon='icon-piliangicon'></i>
                            <span>小程序管理</span>
                        </a>
                    </dd>
                </dl>
            </li> 
            <?php } ?>          
        </ul>
        </div>
    </div>

    <!-- 左侧侧边导航结束 -->
    <!-- 右侧主体内容 -->
    <div class="layui-body" id="larry-body" style="bottom: 0;border-left: solid 2px #2299ee;margin-top:-23px;margin-left:-5px;">
        <div class="layui-tab layui-tab-card larry-tab-box" id="larry-tab" lay-filter="demo" lay-allowclose="true">
            <div class="go-left key-press pressKey" id="titleLeft" title="滚动至最右侧"><i class="larry-icon larry-weibiaoti6-copy"></i> </div>
            <ul class="layui-tab-title">
                <li class="layui-this" id="admin-home"><i class="iconfont icon-diannao1"></i><em>后台首页</em></li>
            </ul>
            <div class="go-right key-press pressKey" id="titleRight" title="滚动至最左侧"><i class="larry-icon larry-right"></i></div> 
            <div class="layui-tab-content" style="min-height: 150px; ">
                <div class="layui-tab-item layui-show" style="height:850px;">
                    <iframe class="larry-iframe" data-id='0' src="{{ route('main') }}"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部区域 -->
    <div class="layui-footer layui-larry-foot" id="larry-footer">
        <div class="layui-mian">  
            <p class="p-admin">
                <span>2018 &copy;</span>
                 牛叔叔科技股份有限公司,版权所有
            </p>
        </div>
    </div>
</div>
<!-- 加载js文件-->                                                                                                                                                                                           
    <script type="text/javascript" src="/assets/common/layui/layui.js"></script> 
    <script type="text/javascript" src="/assets/js/larry.js"></script>
    <script type="text/javascript" src="/assets/js/index.js"></script>
<!-- 锁屏 -->
<div class="lock-screen" style="display: none;">
    <div id="locker" class="lock-wrapper">
        <div id="time"></div>
        <div class="lock-box center">
            <h1>admin</h1>
            <duv class="form-group col-lg-12">
                <input type="password" placeholder='锁屏状态，请输入密码解锁' id="lock_password" class="form-control lock-input" autofocus name="lock_password">
                <button id="unlock" class="btn btn-lock">解锁</button>
            </duv>
        </div>
    </div>
</div>

</body>
</html>