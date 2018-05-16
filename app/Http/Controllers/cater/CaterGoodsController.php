<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterGoods;
use Illuminate\Support\Facades\Auth;
use DB;

class CaterGoodsController extends Controller
{
    //微餐饮-菜品首页
    public function index(Request $request){
    	$admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

    	$good_name = $request -> input("good_name","");

    	$CaterGoods = CaterGoods::where(['admin_id'=>$admin_id]);
         
        if($good_name){
        	$CaterGoods->where("good_name","like","%$good_name%");
        }

        $goods_info = $CaterGoods->orderBy('id','desc')->paginate(2);

    	return view("cater.goods.index",[
    		'goods_info' => $goods_info,
    		'good_name'  => $good_name
    	]);
    }

    //微餐饮-新增/编辑菜品
    public function add_goods(Request $request){
       $goods_id = (int)$request -> input("goods_id",0);

       $goods_info = CaterGoods::where(['id'=>$goods_id])->first();
var_dump($goods_info);
       return view('cater.goods.add_goods',['goods_info'=>$goods_info]);
    }
}
