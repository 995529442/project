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
        $provinces = DB::table("address")->select(['id','name'])->where(['type'=>1,'pid'=>1])->get();
        
    	//获取餐厅信息
        $shops_info = CaterShop::where(['admin_id'=>$admin_id])->first();

    	return view('cater.shop',[
           'provinces'  => $provinces,
           'shops_info' => $shops_info
    	]);
    }

    //微餐饮-获取地址解析
    public function map(Request $request){
    	$province = $request -> input('province','');
        $city = $request -> input('city','');
        $area = $request -> input('area','');
        $address = $request -> input('address','');
        $longitude = "";
        $latitude  = "";

        $detail_address = $province.$city.$area.$address;
        
        //腾讯地图解析地址坐标
        $url = "http://apis.map.qq.com/ws/geocoder/v1/?address=".$detail_address."&key=".env('MAP_KEY', '');

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output = curl_exec($ch);
        curl_close($ch);
        
        $map_obj = json_decode($output);        

        if($map_obj->status == 0){ //解析地址成功
           $longitude = $map_obj->result->location->lng;
           $latitude  = $map_obj->result->location->lat;

        }

        return view("cater.map",[
           'detail_address' => $detail_address,
           'longitude' => $longitude,
           'latitude' => $latitude, 
        ]);
    }
    
    //微餐饮-保存信息
    public function saveShop(Request $request){
        
    }
}
