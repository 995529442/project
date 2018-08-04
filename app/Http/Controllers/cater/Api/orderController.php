<?php
/**
 * User: 35727
 * Date: 2018/7/19
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

date_default_timezone_set('PRC');
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

        return json_encode($return);
      }
      
      if(strtotime(date("Y-m-d")." ".$shop_info->begin_time) > time()){
        $return['errcode'] = -1;
        $return['errmsg'] = "还没到营业时间";

        return json_encode($return);
      }

      if(strtotime(date("Y-m-d")." ".$shop_info->end_time) < time()){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家已打样";

        return json_encode($return);
      }
      
      if((int)$shop_info->is_eat_in == 1 && $cater_type == 1){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家未开启堂食";

        return json_encode($return);
      }

      if((int)$shop_info->is_take_out == 1 && $cater_type == 2){
        $return['errcode'] = -1;
        $return['errmsg'] = "商家未开启外卖";

        return json_encode($return);
      }

      $total_money = 0;  //总价钱
      if($goods_id_arr){
          $goods_id_arr = json_decode($goods_id_arr,true);

          foreach($goods_id_arr as $k=>$v){
              $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

              if($goods_info->isout == 1){
                $return['errcode'] = -1;
                $return['errmsg'] = $goods_info->good_name."还没上架";

                return json_encode($return);
                break;
              }

              if($goods_info->storenum < $v['number']){
                $return['errcode'] = -1;
                $return['errmsg'] = $goods_info->good_name."库存不足，最多能选".$goods_info->storenum."份";

                return json_encode($return);
                break;
              }

              $total_money += $goods_info->now_price * $v['number'];
          }
      }else{
        $return['errcode'] = -1;
        $return['errmsg'] = "您还没有点餐";

        return json_encode($return);
      }

      if($total_money < $shop_info->delivery_fee && $cater_type == 2){
        $return['errcode'] = -1;
        $return['errmsg'] = "起送价为".$shop_info->delivery_fee."元";

        return json_encode($return);
      }

      return json_encode($return);
    }

    /**
     * 下单
     * @param Request $request
     * @return string
     */
    public function pay(Request $request) {
      $admin_id = (int)$request -> input("admin_id",0);
      $user_id = (int)$request -> input("user_id",0);
      $goods_id_arr = $request -> input("goods_id_arr",''); 
      $user_name = $request -> input("user_name",'');
      $phone = $request -> input("phone",'');     
      $cater_type = (int)$request -> input("cater_type",0); //类型，1堂食 2外卖 3排队
      $remark = $request -> input("remark",'') == null?'':$request -> input("remark",'');   //留言

      $return = array(
         "errcode" => -1,
         "errmsg" => '失败'
      );
      try {
           DB::beginTransaction();
           
           //获取用户openid
           $openid = DB::table("cater_users")->where(['id'=>$user_id,'admin_id'=>$admin_id,'isvalid'=>true])->value("openid");

           if(!$openid){
              $return['errmsg'] = '用户不存在';

              return json_encode($return);
           }
           //生成订单号
           $batchcode = date('Ymd') .substr(time(),5). str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

           //获取店铺额外费用
           $shop_info = DB::table("cater_shop")->where(['admin_id'=>$admin_id,'isvalid'=>true]);

           $shipping_fee = $cater_type==2?$shop_info->value("shipping_fee"):0;
           $package_fee = $cater_type==2?$shop_info->value("package_fee"):0;

           //插入订单数据
           $order_data = array(
              "admin_id" => $admin_id,
              "user_id" => $user_id,
              "user_name" => $user_name,
              "phone" => $phone,
              "batchcode" => $batchcode,
              "pay_type" => 0,
              "status" => 0,
              "type" => $cater_type,
              "shipping_fee" => (float)$shipping_fee,
              "package_fee" => (float)$package_fee,
              "remark" => $remark,
              "isvalid" => true
           );

           $order_id = DB::table("cater_orders")->insertGetId($order_data);

           if($order_id){
             $total_money = 0;  //总金额
             $total_num = 0;    //总数量        
             if($goods_id_arr){
                $goods_id_arr = json_decode($goods_id_arr,true);

                $order_goods_arr = array();
                foreach($goods_id_arr as $k=>$v){
                    $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

                    $goods_id_arr[$k]['good_name'] = $goods_info->good_name;

                    $money = $goods_info->now_price * $v['number'];

                    $goods_id_arr[$k]['money'] = $money;

                    $total_money += $money;
                    $total_num += $v['number'];

                    //订单商品详情
                    $goods_data = array(
                       "admin_id" => $admin_id,
                       "order_id" => $order_id,
                       "goods_id" => $goods_info->id,
                       "good_name" => $goods_info->good_name,
                       "price" => $goods_info->now_price,
                       "number" => $v['number'],
                       "total_price" => $money,
                       "isvalid" => true
                    );
                    array_push($order_goods_arr, $goods_data);
                }
             }
             //真实支付金额，菜品总额+配送+包装
             $real_pay = $total_money + $shipping_fee + $package_fee;

             DB::table("cater_orders")->whereId($order_id)->update(['real_pay'=>round($real_pay,2),'total_money'=>round($total_money,2),'total_num'=>$total_num]);

             DB::table("cater_orders_goods")->insert($order_goods_arr);

             DB::commit();

             $return['errocde'] = 1;
             $return['errmsg'] = "成功";

             return json_encode($return);
           }
        }catch (\Exception $exception) {
           DB::rollback();//事务回滚
           throw $exception;
        }
      
    }
}