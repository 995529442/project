<?php
/**
 * User: 35727
 * Date: 2018/7/20
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Librarys\Location;
use App\Librarys\Sms;
use DB;

class getShopController extends Controller
{
    /**
     * 获取店铺信息
     * @param Request $request
     * @return string
     */
    public function getShopInfo(Request $request) {
      $admin_id = (int)$request -> input("admin_id",0);
      $latitude = $request -> input("latitude","");
      $longitude = $request -> input("longitude",""); 
   
      //获取店铺信息
      $shop_info = DB::table("cater_shop")->where(["admin_id"=>$admin_id,"isvalid"=>true])->first();

      if($shop_info){
        $shop_info->provinve = DB::table("address")->whereId($shop_info->province_id)->value("name");
        $shop_info->city = DB::table("address")->whereId($shop_info->city_id)->value("name");
        $shop_info->area = DB::table("address")->whereId($shop_info->area_id)->value("name");

        $shop_info->package_fee = round((float)$shop_info->package_fee,2);
        $shop_info->shipping_fee = round((float)$shop_info->shipping_fee,2);
        $shop_info->delivery_fee = round((float)$shop_info->delivery_fee,2);
        //获取店铺展示图片
        $shop_info->figure_img = DB::table("cater_figure_img")->where(["admin_id"=>$admin_id,"isvalid"=>true,'foreign_id'=>$shop_info->id,'type'=>1])->get();

        if($latitude && $longitude){
          //计算距离
          $location_info = Location::getLocation($admin_id,$latitude,$longitude);

          if($location_info['errcode'] == 1){ //成功
            $distance = (int)$location_info['data'][0]['distance'];

            if($distance > 10000){
              $shop_info->distance = ">10km";
            }elseif($distance <= 10000 && $distance > 1000){
              $shop_info->distance = round($distance/1000,2)."km";
            }else{
              $shop_info->distance = $distance."m";
            }
          }else{
            $shop_info->distance = 0;
          }
        }
      }

      return json_encode($shop_info);
      
    }

    /**
     * 获取首页展示图
     * @param Request $request
     * @return string
     */
    public function getHomeImg(Request $request) {
      $admin_id = (int)$request -> input("admin_id",0);
   
      $shop_info = DB::table("cater_figure_img")->where(["admin_id"=>$admin_id,"isvalid"=>true,"type"=>3])->get();

      return json_encode($shop_info);
      
    }

    /**
     * 验证码
     * @param Request $request
     * @return string
     */
    public function getCode(Request $request) {
       $admin_id = (int)$request -> input("admin_id",0);
       $phone = $request -> input("phone","");
       $code = rand(pow(10,5), pow(10,6)-1);

       \Cache::put('code',$code,3);  //添加缓存,有效期3分钟 

       //发送短信
       $result = Sms::sendSms($admin_id,1,$code,$phone);

       return json_encode($result); 
      
    }
}