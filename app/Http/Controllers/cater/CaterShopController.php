<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterShop;
use Illuminate\Support\Facades\Auth;
use App\Librarys\uploadFile;
use App\Librarys\Location;
use DB;

class CaterShopController extends Controller
{
    //微餐饮-后台首页
    public function index(){
      date_default_timezone_set('PRC');
      var_dump(time());
      var_dump(date("Y-m-d H:i:s",time()));
      exit;
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id; 
        //获取省份数据
        $provinces = DB::table("address")->select(['id','name'])->where(['type'=>1,'pid'=>1])->get();        
    	//获取餐厅信息
        $shops_info = CaterShop::where(['admin_id'=>$admin_id,'isvalid'=>true])->first();

        $cities= "";
        $countris = "";
        $figure_img = [];

        if(!empty($shops_info)){
            $shops_info['show_logo'] = $shops_info['logo'];

            $province_id = $shops_info['province_id'];
            $city_id = $shops_info['city_id'];
            //获取城市数据
            $cities = DB::table("address")->select(['id','name'])->where(['type'=>2,'pid'=>$province_id])->get();
            //获取县区数据
            $countris = DB::table("address")->select(['id','name'])->where(['type'=>3,'pid'=>$city_id])->get();

            //获取预览图
            $figure_img = DB::table("cater_figure_img")->where(['admin_id'=>$admin_id,'foreign_id'=>$shops_info['id'],'type'=>1,'isvalid'=>true])->get();
        }

    	return view('cater.shop.index',[
           'provinces'  => $provinces,
           'cities'     => $cities,
           'countris'   => $countris,
           'shops_info' => $shops_info,
           'figure_img' => $figure_img
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
        $result = Location::declareAddress($detail_address);

        if($result['errcode'] == 1){
            $longitude = $result['data']['longitude'];
            $latitude = $result['data']['latitude'];
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

        $data = array();
        
        $data['name'] = $request -> input('name','');
        $data['begin_time'] = $request -> input('begin_time','');
        $data['end_time'] = $request -> input('end_time','');
        $data['status'] = (int)$request -> input('status',0);
        $data['province_id'] = (int)$request -> input('provid',0);
        $data['city_id'] = (int)$request -> input('cityid',0);
        $data['area_id'] = (int)$request -> input('areaid',0);
        $data['address'] = $request -> input('address','');
        $data['longitude'] = $request -> input('longitude','');
        $data['latitude'] = $request -> input('latitude','');
        $data['introduce'] = $request -> input('introduce','');
        $data['phone'] = $request -> input('phone','');
        $data['logo'] = $request -> input('logo','');
        $data['is_eat_in'] = (int)$request -> input('is_eat_in',0);
        $data['is_take_out'] = (int)$request -> input('is_take_out',0);
        $data['shipping_fee'] = $request -> input('shipping_fee','');
        $data['package_fee'] = $request -> input('package_fee','');
        $data['delivery_km'] = $request -> input('delivery_km','');
        $data['delivery_fee'] = $request -> input('delivery_fee','');

        if($shop_id > 0){
            $cater_shop = DB::table("cater_shop")->whereId($shop_id)->update($data);
        }else{
            $admins   = Auth::guard('admins')->user();
            $admin_id = (int)$admins->id;

            $data['admin_id'] = $admin_id;
            $data['isvalid']  = true;

            $shop_id = DB::table("cater_shop")->insertGetId($data);
        }
        $figure_img_id = $request -> input('figure_img_id','');
        $figure_img = $request -> input('figure_img','');

        //商家展示图
        if($figure_img){
            for($k=0;$k<count($figure_img_id);$k++){
                $insert_data = array(
                   "admin_id" => Auth::guard('admins')->user()->id,
                   "img_path" =>$figure_img[$k],
                   "foreign_id" => $shop_id,
                   "type" => 1,
                   "isvalid" => true
                );
                
                if((int)$figure_img_id[$k] > 0){  //修改
                  DB::table("cater_figure_img")->whereId((int)$figure_img_id[$k])->update(['img_path'=>$figure_img[$k],]);
                }else{
                  DB::table("cater_figure_img")->insert($insert_data);
                }
            }
        }

        return redirect('cater/shop/home');
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

    //微餐饮-删除首页展示图片
    public function delFigureImg(Request $request){
       $img_id = (int)$request -> input('img_id',0);

       $result = DB::table("cater_figure_img")->whereId($img_id)->update(['isvalid'=>false]);

       if($result){
          return json_encode(['errcode'=>1,'errmsg'=>'成功']);
       }else{
          return json_encode(['errcode'=>-1,'errmsg'=>'失败']);
       }
    }
}
