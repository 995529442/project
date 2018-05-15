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
       Route::get('shop', 'cater\CaterShopController@shop')->name('cater.shop'); //餐厅管理
       Route::post('saveShop', 'cater\CaterShopController@saveShop')->name('cater.saveShop'); //餐厅管理
       Route::post('getAddress', 'cater\CaterShopController@getAddress')->name('cater.getAddress'); //获取地址
    });    
});

