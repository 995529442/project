<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterShop;
use Illuminate\Support\Facades\Auth;
use App\Librarys\uploadFile;
use DB;

class CaterShopController extends Controller
{
    //微餐饮-后台首页
    public function index(){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id; 
        //获取省份数据
        $provinces = DB::table("address")->select(['id','name'])->where(['type'=>1,'pid'=>1])->get();        
    	//获取餐厅信息
        $shops_info = CaterShop::where(['admin_id'=>$admin_id,'isvalid'=>true])->first();

        $cities= "";
        $countris = "";

        if(!empty($shops_info)){
            $shops_info['show_logo'] = $shops_info['logo'];

            $province_id = $shops_info['province_id'];
            $city_id = $shops_info['city_id'];
            //获取城市数据
            $cities = DB::table("address")->select(['id','name'])->where(['type'=>2,'pid'=>$province_id])->get();
            //获取县区数据
            $countris = DB::table("address")->select(['id','name'])->where(['type'=>3,'pid'=>$city_id])->get();
        }

    	return view('cater.shop.index',[
           'provinces'  => $provinces,
           'cities'     => $cities,
           'countris'   => $countris,
           'shops_info' => $shops_info
    	]);
    }
    
     //微餐饮-获取省市区等信息
    public function getAddress(Request $request){
       $provid = (int)$request -> input('provid',0);
       $cityid = (int)$request -> input('cityid',0);

       if($provid > 0){ //获取该省下的城市列表
          $data = DB::table("address")->select(['id','name'])->where(['type'=>2,'pid'=>$provid])->get();
       }elseif($cityid > 0){
          $data = DB::table("address")->select(['id','name'])->where(['type'=>3,'pid'=>$cityid])->get();
       }

       return $data;
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

        return view("cater.shop.map",[
           'detail_address' => $detail_address,
           'longitude' => $longitude,
           'latitude' => $latitude, 
        ]);
    }
    
    //微餐饮-保存信息
    public function saveShop(Request $request){
        $shop_id    = (int)$request -> input('shop_id',0);

        if($shop_id > 0){
            $cater_shop = CaterShop::findOrFail($shop_id);
        }else{
            $cater_shop = new CaterShop;

            $admins   = Auth::guard('admins')->user();
            $admin_id = (int)$admins->id;

            $cater_shop->admin_id = $admin_id;
            $cater_shop->isvalid  = true;
        }
        $cater_shop->name        = $request -> input('name','');
        $cater_shop->begin_time  = $request -> input('begin_time','');
        $cater_shop->end_time    = $request -> input('end_time','');
        $cater_shop->status      = (int)$request -> input('status',0);
        $cater_shop->province_id = (int)$request -> input('provid',0);
        $cater_shop->city_id     = (int)$request -> input('cityid',0);
        $cater_shop->area_id     = (int)$request -> input('areaid',0);
        $cater_shop->address     = $request -> input('address','');
        $cater_shop->longitude   = $request -> input('longitude','');
        $cater_shop->latitude    = $request -> input('latitude','');
        $cater_shop->introduce   = $request -> input('introduce','');
        $cater_shop->phone       = $request -> input('phone','');
        $cater_shop->logo        = $request -> input('logo','');
        $cater_shop->is_eat_in   = (int)$request -> input('is_eat_in',0);
        $cater_shop->is_take_out = (int)$request -> input('is_take_out',0);
        $cater_shop->shipping_fee= $request -> input('shipping_fee','');
        $cater_shop->package_fee = $request -> input('package_fee','');
        $cater_shop->delivery_km = $request -> input('delivery_km','');

        $result = $cater_shop->save();

        if($result){
            return redirect('cater/shop/index');
        }
    }

     //微餐饮-上传图片接口
    public function upload(Request $request){
        if ($request->isMethod('post')) {
            $admins   = Auth::guard('admins')->user();
            $admin_id = $admins->id;

            $result = uploadFile::uploadImg($admin_id,$_FILES,'/cater/shop/');
        } else {
            $result = ['errcode'=>-1,'errmsg'=>'参数错误'];
        }
        return json_encode($result);
    }
}
