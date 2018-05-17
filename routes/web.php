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
Route::get('login/index', 'LoginController@index')->name('login.index');                    //登录页
Route::post('login/checkLogin', 'LoginController@checkLogin')->name('login.checkLogin');    //登录验证
Route::get('login/logout', 'LoginController@logout')->name('login.logout');                 //退出登录
Route::get('login/captcha', 'LoginController@captcha')->name('login.captcha');              //验证码

//要验证登录状态的路由
Route::group(['middleware'=>'AdminLogin'],function(){
    Route::get('/', 'IndexController@index')->name('index');   
    Route::get('Index/index', 'IndexController@index')->name('index');
    Route::get('Index/main', 'IndexController@main')->name('main');

    //微餐饮路由
    Route::group(['prefix'=>'cater'],function(){
       //餐厅信息
       Route::group(['prefix'=>'shop'],function(){
          Route::get('index', 'cater\CaterShopController@index')->name('cater.shop.index'); //餐厅管理
          Route::post('saveShop', 'cater\CaterShopController@saveShop')->name('cater.shop.saveShop'); //保存餐厅
          Route::any('map', 'cater\CaterShopController@map')->name('cater.shop.map'); //定位地图
          Route::post('getAddress', 'cater\CaterShopController@getAddress')->name('cater.shop.getAddress'); //获取省市区等信息
          Route::post('upload', 'cater\CaterShopController@upload')->name('cater.shop.upload'); //上传logo图片
       });

       //分类管理
       Route::group(['prefix'=>'category'],function(){
          Route::get('index', 'cater\CaterCategoryController@index')->name('cater.category.index'); //分类管理
          Route::post('operate', 'cater\CaterCategoryController@operate')->name('cater.category.operate'); //分类操作
          Route::any('add_cate', 'cater\CaterCategoryController@add_cate')->name('cater.category.add_cate'); //新增分类
          Route::any('save_cate', 'cater\CaterCategoryController@save_cate')->name('cater.category.save_cate'); //保存分类
       });

       //菜品管理
       Route::group(['prefix'=>'goods'],function(){
          Route::get('index', 'cater\CaterGoodsController@index')->name('cater.goods.index'); //菜品管理
          Route::get('add_goods', 'cater\CaterGoodsController@add_goods')->name('cater.goods.add_goods'); //菜品管理
          Route::post('upload', 'cater\CaterGoodsController@upload')->name('cater.goods.upload'); //上传logo图片
          Route::any('save_goods', 'cater\CaterGoodsController@save_goods')->name('cater.goods.save_goods'); //保存商品
          Route::post('del_goods', 'cater\CaterGoodsController@del_goods')->name('cater.goods.del_goods'); //删除商品
       });
    });    
});

