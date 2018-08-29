<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//定义登录注册路由
Route::get('login/home', 'LoginController@index')->name('login.index');                    //登录页
Route::post('login/checkLogin', 'LoginController@checkLogin')->name('login.checkLogin');    //登录验证
Route::get('login/logout', 'LoginController@logout')->name('login.logout');                 //退出登录
Route::get('login/captcha', 'LoginController@captcha')->name('login.captcha');              //验证码

//要验证登录状态的路由
Route::group(['middleware' => 'AdminLogin'], function () {
    Route::get('/', 'IndexController@index')->name('index');
    Route::get('Index/home', 'IndexController@index')->name('index');
    //Route::get('Index', 'IndexController@index')->name('index');
    //Route::get('Index/main', 'IndexController@main')->name('main');
    Route::get('main.html', 'IndexController@main')->name('main');
    Route::get('Index/manage', 'IndexController@manage')->name('manage');
    Route::get('Index/add_admin', 'IndexController@add_admin')->name('add_admin'); //新增管理员
    Route::post('Index/save_admin', 'IndexController@save_admin')->name('save_admin'); //保存管理员信息
    Route::post('Index/reset_admin', 'IndexController@reset_admin')->name('reset_admin'); //重置管理员密码
    Route::get('Index/module', 'IndexController@module')->name('module'); //模块分配  
    Route::post('Index/saveModule', 'IndexController@saveModule')->name('saveModule'); //模块分配保存   

    Route::any('Index/mail', 'IndexController@mail')->name('mail'); //邮件设置 
    Route::any('Index/saveMail', 'IndexController@saveMail')->name('saveMail'); //保存邮件设置 

    Route::any('Index/sms', 'IndexController@sms')->name('sms'); //短信设置 
    Route::any('Index/saveSms', 'IndexController@saveSms')->name('saveSms'); //保存短信设置

    Route::any('Index/sms_template', 'IndexController@smsTemplate')->name('smsTemplate'); //短信模板页面 
    Route::any('Index/add_sms_template', 'IndexController@addSmsTemplate')->name('addSmsTemplate'); //新增短信模板页面
    Route::any('Index/save_sms_template', 'IndexController@saveSmsTemplate')->name('saveSmsTemplate'); //保存短信模板页面  
    Route::any('Index/del_sms_template', 'IndexController@delSmsTemplate')->name('delSmsTemplate'); //删除短信模板页面

    Route::any('Index/send_log', 'IndexController@sendLog')->name('sendLog'); //发送记录

    //测试短信、邮件
    Route::any('Index/test_sms', 'IndexController@testSms')->name('testSms'); //测试短信

    Route::any('Index/get_orders', 'IndexController@getOrders')->name('getOrders'); //首页获取订单

    //微餐饮路由
    Route::group(['prefix' => 'cater'], function () {
        //餐厅信息
        Route::group(['prefix' => 'shop'], function () {
            Route::get('home', 'cater\CaterShopController@index')->name('cater.shop.index'); //餐厅管理
            Route::post('saveShop', 'cater\CaterShopController@saveShop')->name('cater.shop.saveShop'); //保存餐厅
            Route::any('map', 'cater\CaterShopController@map')->name('cater.shop.map'); //定位地图
            Route::post('getAddress', 'cater\CaterShopController@getAddress')->name('cater.shop.getAddress'); //获取省市区等信息
            Route::post('upload', 'cater\CaterShopController@upload')->name('cater.shop.upload'); //上传logo图片
            Route::post('delFigureImg', 'cater\CaterShopController@delFigureImg')->name('cater.shop.delFigureImg'); //上传logo图片
        });

        //首页管理
        Route::group(['prefix' => 'home'], function () {
            Route::any('home', 'cater\CaterHomeController@index')->name('cater.home.index'); //首页管理
            Route::any('save', 'cater\CaterHomeController@save')->name('cater.home.save'); //保存首页管理
            Route::any('upload', 'cater\CaterHomeController@upload')->name('cater.home.upload'); //上传
            Route::any('delFigureImg', 'cater\CaterHomeController@delFigureImg')->name('cater.home.delFigureImg'); //删除
        });

        //分类管理
        Route::group(['prefix' => 'category'], function () {
            Route::get('home', 'cater\CaterCategoryController@index')->name('cater.category.index'); //分类管理
            Route::post('operate', 'cater\CaterCategoryController@operate')->name('cater.category.operate'); //分类操作
            Route::any('add_cate', 'cater\CaterCategoryController@add_cate')->name('cater.category.add_cate'); //新增分类
            Route::any('save_cate', 'cater\CaterCategoryController@save_cate')->name('cater.category.save_cate'); //保存分类
        });

        //菜品管理
        Route::group(['prefix' => 'goods'], function () {
            Route::get('home', 'cater\CaterGoodsController@index')->name('cater.goods.index'); //菜品管理
            Route::get('add_goods', 'cater\CaterGoodsController@add_goods')->name('cater.goods.add_goods'); //菜品管理
            Route::post('upload', 'cater\CaterGoodsController@upload')->name('cater.goods.upload'); //上传logo图片
            Route::any('save_goods', 'cater\CaterGoodsController@save_goods')->name('cater.goods.save_goods'); //保存商品
            Route::post('del_goods', 'cater\CaterGoodsController@del_goods')->name('cater.goods.del_goods'); //删除商品
            Route::any('delFigureImg', 'cater\CaterGoodsController@delFigureImg')->name('cater.goods.delFigureImg'); //删除展示图片
        });

        //用户管理
        Route::group(['prefix' => 'users'], function () {
            Route::get('home', 'cater\CaterUsersController@index')->name('cater.users.index'); //用户管理
            Route::get('add_currency', 'cater\CaterUsersController@add_currency')->name('cater.users.add_currency'); //购物币充值
            Route::post('save_currency', 'cater\CaterUsersController@save_currency')->name('cater.users.save_currency'); //购物币保存
            Route::get('currency_log', 'cater\CaterUsersController@currency_log')->name('cater.users.currency_log'); //购物币日志
        });

        //小程序管理
        Route::group(['prefix' => 'system'], function () {
            Route::get('home', 'cater\CaterSystemController@index')->name('cater.system.index'); //小程序管理
            Route::any('upload', 'cater\CaterSystemController@upload')->name('cater.system.upload'); //上传证书
            Route::post('saveSystem', 'cater\CaterSystemController@saveSystem')->name('cater.system.saveSystem'); //保存信息
        });

        //订单管理
        Route::group(['prefix' => 'orders'], function () {
            Route::get('home', 'cater\CaterOrdersController@index')->name('cater.orders.index'); //订单管理
            Route::get('orderGoods', 'cater\CaterOrdersController@orderGoods')->name('cater.orders.orderGoods'); //订单商品详情
            Route::post('operate', 'cater\CaterOrdersController@operate')->name('cater.orders.operate'); //订单操作
            Route::get('reject_refund', 'cater\CaterOrdersController@reject_refund')->name('cater.orders.reject_refund'); //订单拒绝退款页面
        });

        //餐桌管理
        Route::group(['prefix' => 'desk'], function () {
            Route::get('home', 'cater\CaterDeskController@index')->name('cater.desk.index'); //餐桌管理
            Route::get('addDesk', 'cater\CaterDeskController@addDesk')->name('cater.desk.addDesk'); //添加餐桌
            Route::any('saveDesk', 'cater\CaterDeskController@saveDesk')->name('cater.desk.saveDesk'); //保存餐桌
            Route::any('operate', 'cater\CaterDeskController@operate')->name('cater.desk.operate'); //删除餐桌，生成二维码
        });

        //模板管理
        Route::group(['prefix' => 'template'], function () {
            Route::get('home', 'cater\CaterTemplateController@index')->name('cater.template.index'); //模板管理
            Route::get('addTemplate', 'cater\CaterTemplateController@addTemplate')->name('cater.template.addTemplate'); //新增
            Route::post('saveTemplate', 'cater\CaterTemplateController@saveTemplate')->name('cater.template.saveTemplate'); //保存
            Route::post('delTemplate', 'cater\CaterTemplateController@delTemplate')->name('cater.template.delTemplate'); //保存
        });

		//统计管理
		Route::group(['prefix' => 'statistics'], function () {
			Route::get('home', 'cater\CaterStatisticsController@index')->name('cater.statistics.index'); //统计管理
		});
    });
});

