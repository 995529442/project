<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterShop;
use Illuminate\Support\Facades\Auth;
use DB;

class CaterShopController extends Controller
{
    //微餐饮-后台首页
    public function shop(){
    	$admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;
    	//获取省份数据
    	$provinces = DB::table("provinces")->select(['provinceid','province'])->get();

    	//获取餐厅信息
        $shops_info = CaterShop::where(['admin_id'=>$admin_id])->first();

    	return view('cater.shop',[
           'provinces'  => $provinces,
           'shops_info' => $shops_info
    	]);
    }

    //微餐饮-保存信息
    public function saveShop(Request $request){
    	
    }

    //微餐饮-获取省市区
    public function getAddress(Request $request){
    	$type       = (int)$request -> input("type",0); //类型 1 获取省，2获取市，3获取区
    	$provinceid = (int)$request -> input("provinceid","");
    	$cityid     = (int)$request -> input("cityid","");

    	if($type == 1){

    	}elseif($type == 2 && $provinceid > 0){ //获取城市
           $result = DB::table("cities")->where("provinceid",$provinceid)->select(['cityid','city'])->get();
    	}elseif($type == 3 && $cityid > 0){    //获取区县
           $result = DB::table("areas")->where("cityid",$cityid)->select(['areaid','area'])->get();
    	}

    	return json_encode($result);
    }
}
