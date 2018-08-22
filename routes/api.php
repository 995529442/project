<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'cater'], function () {
    Route::any('getUserInfo/getUsers', 'cater\Api\getUserInfoController@getUsers'); //获取用户信息
    Route::any('getUserInfo/getAddress', 'cater\Api\getUserInfoController@getAddress'); //获取用户地址
    Route::any('getUserInfo/getOneAddress', 'cater\Api\getUserInfoController@getOneAddress'); //获取用户单条地址
    Route::any('getUserInfo/addAddress', 'cater\Api\getUserInfoController@addAddress'); //新增用户地址
    Route::any('getUserInfo/delAddress', 'cater\Api\getUserInfoController@delAddress'); //删除用户地址
    Route::any('getUserInfo/getDefaultAddress', 'cater\Api\getUserInfoController@getDefaultAddress'); //获取用户默认地址
    Route::any('getUserInfo/getMyCurrency', 'cater\Api\getUserInfoController@getMyCurrency'); //获取用户购物币
    Route::any('getUserInfo/getCurrency', 'cater\Api\getUserInfoController@getCurrency'); //获取购物币消费记录
    Route::any('getUserInfo/getOneUsersSetPassword', 'cater\Api\getUserInfoController@getOneUsersSetPassword'); //获取用户是否设置支付密码

    Route::any('getGoods/getHotRecGoods', 'cater\Api\getGoodsController@getHotRecGoods'); //获取首页菜品
    Route::any('getGoods/getCatGoods', 'cater\Api\getGoodsController@getCatGoods'); //分类和菜品
    Route::any('getGoods/getSubmitGoods', 'cater\Api\getGoodsController@getSubmitGoods'); //获取结算菜品详情
    Route::any('getGoods/getOneGoods', 'cater\Api\getGoodsController@getOneGoods'); //获取单个菜品详情

    Route::any('getShop/getShopInfo', 'cater\Api\getShopController@getShopInfo'); //获取店铺信息
    Route::any('getShop/getHomeImg', 'cater\Api\getShopController@getHomeImg'); //获取首页展示图
    Route::any('getShop/getCode', 'cater\Api\getShopController@getCode'); //获取验证码
    Route::any('getShop/savePassword', 'cater\Api\getShopController@savePassword'); //修改支付密码

    Route::any('order/checkSubmit', 'cater\Api\orderController@checkSubmit'); //结算
    Route::any('order/pay', 'cater\Api\orderController@pay'); //下单
    Route::any('order/getOrders', 'cater\Api\orderController@getOrders'); //获取我的订单
    Route::any('order/operate', 'cater\Api\orderController@operate'); //订单处理
});
