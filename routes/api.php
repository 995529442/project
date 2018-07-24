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
    
    Route::any('getGoods/getHotRecGoods', 'cater\Api\getGoodsController@getHotRecGoods'); //获取首页菜品
    Route::any('getGoods/getCatGoods', 'cater\Api\getGoodsController@getCatGoods'); //分类和菜品

    Route::any('getShop/getShopInfo', 'cater\Api\getShopController@getShopInfo'); //获取店铺信息
    Route::any('getShop/getHomeImg', 'cater\Api\getShopController@getHomeImg'); //获取首页展示图
});
