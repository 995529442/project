<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\CaterOrders;
use App\Model\CaterOrdersGoods;
use DB;

class CaterOrdersController extends Controller
{
    //微餐饮-订单首页
    public function index(Request $request){
       $admins   = Auth::guard('admins')->user();
       $admin_id = $admins->id;
       
       $batchcode = $request -> input("batchcode",'');
       $weixin_name = $request -> input("weixin_name",'');
       $phone = $request -> input("phone",'');
       $type = (int)$request -> input("type",0);
       $pay_type = (int)$request -> input("pay_type",-1);
       $status = (int)$request -> input("status",-2);

       $order = DB::table("cater_orders as or")
       ->leftJoin("cater_users as us",'or.user_id','us.id')
       ->where(["or.admin_id"=>$admin_id,"or.isvalid"=>true])
       ->select(['or.id as order_id','or.batchcode','or.phone','or.pay_type','or.status','or.type','or.real_pay','or.total_num','or.remark','us.weixin_name']);

       if($batchcode){
       	  $order -> where("or.batchcode","like","%$batchcode%");
       }

       if($weixin_name){
       	  $order -> where("us.weixin_name","like","%$weixin_name%");
       }

       if($phone){
       	  $order -> where("or.phone","like","%$phone%");
       }

       if($type){
       	  $order -> where("or.type","=",$type);
       }

       if($pay_type != -1){
       	  $order -> where("or.pay_type","=",$pay_type);
       }

       if($status != -2){
       	  $order -> where("or.status","=",$status);
       }

       $order_info = $order ->orderBy('or.id','desc')-> paginate(12);

       return view('cater.orders.index',[
       	'order_info'=>$order_info,
       	'batchcode' => $batchcode,
       	'weixin_name' => $weixin_name,
       	'phone' => $phone,
       	'type' => $type,
       	'pay_type' => $pay_type, 
       	'status' => $status       	      	
       ]);
    }
    //微餐饮-订单商品详情
    public function orderGoods(Request $request){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id;

        $order_id = $request -> input("order_id",0);

        if($order_id > 0){
	       $order_detail = DB::table("cater_orders as or")
	       ->leftJoin("cater_users as us",'or.user_id','us.id')
	       ->where(["or.id"=>$order_id,"or.isvalid"=>true])
	       ->select(['or.id as order_id','or.batchcode','or.phone','or.pay_type','or.status','or.type','or.real_pay','or.total_num','or.remark','us.weixin_name','or.pay_time','or.shipping_time','or.shipping_con_time','or.confirm_time','or.recovery_time','or.shipping_fee','or.package_fee','or.total_money','or.reject_reason'])
	       ->first();

	       //获取订单商品详情
           $order_goods = CaterOrdersGoods::where(['admin_id'=>$admin_id,'order_id'=>$order_id,'isvalid'=>true])->get();

	       return view('cater.orders.order_goods',['order_detail'=>$order_detail,'order_goods'=>$order_goods]);
        }
    }

    //微餐饮-订单商品详情
    public function operate(Request $request){
       $order_id = (int)$request -> input("order_id",0);
       $type = $request -> input("type",'');
       $reject_reason = $request -> input("reject_reason",'');

       $return = array(
          'errcode' => -1,
          'errmsg'  => "失败"
       );

       if($order_id > 0){
       	  $data = array();

          switch($type){
          	case 'accept':
          	  $data['status'] = 2;
          	break;
          	case 'reject':
          	  $data['status'] = 9;
          	break;
          	case 'send':
          	  $data['status'] = 3;
          	  $data['shipping_time'] = time();
          	break;
          	case 'confirm_send':
          	  $data['status'] = 4;
          	  $data['shipping_con_time'] = time();
          	break;
          	case 'confirm':
          	  $data['status'] = 5;
          	  $data['confirm_time'] = time();
          	break;
          	case 'confirm_refund':
          	  $data['status'] = 7;
          	break;
          	case 'reject_refund':
          	  $data['status'] = 8;
          	  $data['reject_reason'] = $reject_reason;
          	break;
          	case 'cancel':
          	  $data['status'] = -1;
          	  $data['recovery_time'] = time();
          	break;
          	default:
          	break;
          }

          $result = CaterOrders::where(['id'=>$order_id,'isvalid'=>true])->update($data);

          if($result){
          	$return['errcode'] = 1;
          	$return['errmsg']  = "成功";
          }
       }
       return json_encode($return);
    }

    //微餐饮-订单拒绝退款
    public function reject_refund(Request $request){
    	$order_id = (int)$request -> input("order_id",0);

        return view('cater.orders.reject_refund',['order_id'=>$order_id]);
    }
    
}
