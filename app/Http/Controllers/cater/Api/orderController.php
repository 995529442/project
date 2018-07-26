<?php
/**
 * User: 35727
 * Date: 2018/7/19
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class orderController extends Controller
{
    /**
     * 结算
     * @param Request $request
     * @return string
     */
    public function checkSubmit(Request $request) {
      $admin_id = (int)$request -> input("admin_id",0);
      $goods_id_arr = $request -> input("goods_id_arr",'');      
      $cater_type = (int)$request -> input("cater_type",0); //类型，1堂食 2外卖 3排队

      //查找店铺信息
      $return = array(
         "errcode" => 1,
         "errmsg" => '成功'
      );

      $shop_info = DB::table("cater_shop")->where(['admin_id'=>$admin_id,'isvalid'=>true])->first();

      if((int)$shop_info->status == 2){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家已打样";
      }
      
      if(strtotime(date("Y-m-d")." ".$shop_info->begin_time) > time()){
        $return['errcode'] = -1;
        $return['errmsg'] = "还没到营业时间";
      }

      if(strtotime(date("Y-m-d")." ".$shop_info->end_time) < time()){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家已打样";
      }
      
      if((int)$shop_info->is_eat_in == 1 && $cater_type == 1){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家未开启堂食";
      }

      if((int)$shop_info->is_take_out == 1 && $cater_type == 2){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家未开启外卖";
      }

      $total_money = 0;  //总价钱
      if($goods_id_arr){
          $goods_id_arr = json_decode($goods_id_arr,true);

          foreach($goods_id_arr as $k=>$v){
              $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

              if($goods_info->isout == 1){
                $return['errcode'] = -1;
                $return['errmsg'] = $goods_info->good_name."还没上架";
                break;
              }

              if($goods_info->storenum < $v['number']){
                $return['errcode'] = -1;
                $return['errmsg'] = $goods_info->good_name."库存不足，最多能选".$goods_info->storenum."份";
                break;
              }

              $total_money += $goods_info->now_price * $v['number'];
          }
      }else{
        $return['errcode'] = -1;
        $return['errmsg'] = "您还没有点餐";
      }

      if($total_money < $shop_info->delivery_fee && $cater_type == 2){
        $return['errcode'] = -1;
        $return['errmsg'] = "起送价为".$shop_info->delivery_fee."元";
      }
      
      return json_encode($return);
    }
}