<?php
/**
 * User: 35727
 * Date: 2018/7/20
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
      
      //获取店铺信息
      $shop_info = DB::table("cater_shop")->where(["admin_id"=>$admin_id,"isvalid"=>true])->first();

      if($shop_info){
        $shop_info->provinve = DB::table("address")->whereId($shop_info->province_id)->value("name");
        $shop_info->city = DB::table("address")->whereId($shop_info->city_id)->value("name");
        $shop_info->area = DB::table("address")->whereId($shop_info->area_id)->value("name");

        //获取店铺展示图片
        $shop_info->figure_img = DB::table("cater_figure_img")->where(["admin_id"=>$admin_id,"isvalid"=>true,'foreign_id'=>$shop_info->id,'type'=>1])->get();
      }

      return json_encode($shop_info);
      
    }
}