<?php

namespace App\Http\Controllers\cater;

date_default_timezone_set('PRC');

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\CaterOrders;
use App\Model\CaterOrdersGoods;
use DB;

class CaterOrdersController extends Controller
{
    //微餐饮-订单首页
    public function index(Request $request)
    {
        $admins = Auth::guard('admins')->user();
        $admin_id = $admins->id;

        $batchcode = $request->input("batchcode", '');
        $weixin_name = $request->input("weixin_name", '');
        $phone = $request->input("phone", '');
        $type = (int)$request->input("type", 0);
        $pay_type = (int)$request->input("pay_type", -1);
        $status = (int)$request->input("status", -2);

        $order = DB::table("cater_orders as or")
            ->leftJoin("cater_users as us", 'or.user_id', 'us.id')
            ->where(["or.admin_id" => $admin_id, "or.isvalid" => true])
            ->select(['or.id as order_id', 'or.batchcode', 'or.phone', 'or.pay_type', 'or.status', 'or.type', 'or.real_pay', 'or.total_num', 'or.remark', 'or.create_time', 'us.weixin_name']);

        if ($batchcode) {
            $order->where("or.batchcode", "like", "%$batchcode%");
        }

        if ($weixin_name) {
            $order->where("us.weixin_name", "like", "%$weixin_name%");
        }

        if ($phone) {
            $order->where("or.phone", "like", "%$phone%");
        }

        if ($type) {
            $order->where("or.type", "=", $type);
        }

        if ($pay_type != -1) {
            $order->where("or.pay_type", "=", $pay_type);
        }

        if ($status != -2) {
            $order->where("or.status", "=", $status);
        }

        $order_info = $order->orderBy('or.id', 'desc')->paginate(12);

        return view('cater.orders.index', [
            'order_info' => $order_info,
            'batchcode' => $batchcode,
            'weixin_name' => $weixin_name,
            'phone' => $phone,
            'type' => $type,
            'pay_type' => $pay_type,
            'status' => $status
        ]);
    }

    //微餐饮-订单商品详情
    public function orderGoods(Request $request)
    {
        $admins = Auth::guard('admins')->user();
        $admin_id = $admins->id;

        $order_id = $request->input("order_id", 0);

        if ($order_id > 0) {
            $order_detail = DB::table("cater_orders as or")
                ->leftJoin("cater_users as us", 'or.user_id', 'us.id')
                ->where(["or.id" => $order_id, "or.isvalid" => true])
                ->select(['or.id as order_id', 'or.batchcode', 'or.phone', 'or.pay_type', 'or.status', 'or.type', 'or.real_pay', 'or.total_num', 'or.remark', 'us.weixin_name', 'or.pay_time', 'or.shipping_time', 'or.shipping_con_time', 'or.confirm_time', 'or.recovery_time', 'or.shipping_fee', 'or.package_fee', 'or.total_money', 'or.reject_reason'])
                ->first();

            //获取订单商品详情
            $order_goods = CaterOrdersGoods::where(['admin_id' => $admin_id, 'order_id' => $order_id, 'isvalid' => true])->get();

            return view('cater.orders.order_goods', ['order_detail' => $order_detail, 'order_goods' => $order_goods]);
        }
    }

    //微餐饮-订单操作
    public function operate(Request $request)
    {
        $order_id = (int)$request->input("order_id", 0);
        $type = $request->input("type", '');
        $reject_reason = $request->input("reject_reason", '');

        $return = array(
            'errcode' => -1,
            'errmsg' => "失败"
        );

        if ($order_id > 0) {
            $data = array();

            $order_info = DB::table("cater_orders")->whereId($order_id)->first();

            switch ($type) {
                case 'accept':
                    $data['status'] = 2;
                    break;
                case 'reject':  //拒单
                    $data['status'] = 9;

                    try {
                        DB::beginTransaction();

                        $payment_type = (int)$order_info->payment_type;
                        $user_id = (int)$order_info->user_id;
                        $real_pay = (float)$order_info->real_pay;
                        $user_name = $order_info->user_name;
                        $batchcode = $order_info->batchcode;

                        if ($payment_type == 0) { //微信支付

                        } elseif ($payment_type == 1) { //购物币支付
                            DB::table("cater_users")->whereId($user_id)->increment("currency_money", $real_pay);

                            //记录日志
                            DB::table("cater_currency_log")->insert([
                                "admin_id" => Auth::guard('admins')->user()->id,
                                "operate_from" => Auth::guard('admins')->user()->username,
                                "user_id" => $user_id,
                                "operate_to" => $user_name,
                                "remark" => "商家拒单，返还" . $real_pay . "元，订单号：" . $batchcode,
                                "create_time" => time(),
                                "type" => 1,
                                "currency_money" => $real_pay,
                                "isvalid" => true
                            ]);
                        }

                        $result = CaterOrders::where(['id' => $order_id, 'isvalid' => true])->update($data);

                        DB::commit();

                        if ($return) {
                            $return['errcode'] = 1;
                            $return['errmsg'] = "成功";
                        }

                        return json_encode($return);

                    } catch (\Exception $exception) {
                        DB::rollback();//事务回滚
                        throw $exception;
                    }
                    break;
                case 'send':
                    $data['status'] = 3;
                    $data['shipping_time'] = time();
                    break;
                case 'confirm_send':
                    $data['status'] = 4;
                    $data['shipping_con_time'] = time();
                    break;
                case 'confirm':  //完成订单
                    $data['status'] = 5;
                    $data['confirm_time'] = time();

                    try {
                        DB::beginTransaction();

                        $user_id = (int)$order_info->user_id;

                        $result = CaterOrders::where(['id' => $order_id, 'isvalid' => true])->update($data);

                        //更新用户数据
                        $user_id = $order_info->user_id;
                        $real_pay = $order_info->real_pay;

                        $user_model = DB::table("cater_users")->whereId($user_id);
                        $user_model->increment("order_complete_num");
                        $user_model->increment("total_money", (float)$real_pay);

                        DB::commit();

                        if ($return) {
                            $return['errcode'] = 1;
                            $return['errmsg'] = "成功";
                        }

                        return json_encode($return);

                    } catch (\Exception $exception) {
                        DB::rollback();//事务回滚
                        throw $exception;
                    }
                    break;
                case 'confirm_refund':  //确认退款
                    $data['status'] = 7;

                    try {
                        DB::beginTransaction();

                        $payment_type = (int)$order_info->payment_type;
                        $user_id = (int)$order_info->user_id;
                        $real_pay = (float)$order_info->real_pay;
                        $user_name = $order_info->user_name;
                        $batchcode = $order_info->batchcode;

                        if ($payment_type == 0) { //微信支付

                        } elseif ($payment_type == 1) { //购物币支付
                            DB::table("cater_users")->whereId($user_id)->increment("currency_money", $real_pay);

                            //记录日志
                            DB::table("cater_currency_log")->insert([
                                "admin_id" => Auth::guard('admins')->user()->id,
                                "operate_from" => Auth::guard('admins')->user()->username,
                                "user_id" => $user_id,
                                "operate_to" => $user_name,
                                "remark" => "商家确认退款，返还" . $real_pay . "元，订单号：" . $batchcode,
                                "create_time" => time(),
                                "type" => 1,
                                "currency_money" => $real_pay,
                                "isvalid" => true
                            ]);
                        }

                        $result = CaterOrders::where(['id' => $order_id, 'isvalid' => true])->update($data);

                        DB::commit();

                        if ($return) {
                            $return['errcode'] = 1;
                            $return['errmsg'] = "成功";
                        }

                        return json_encode($return);

                    } catch (\Exception $exception) {
                        DB::rollback();//事务回滚
                        throw $exception;
                    }
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

            $result = CaterOrders::where(['id' => $order_id, 'isvalid' => true])->update($data);

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
            }
        }
        return json_encode($return);

    }

    //微餐饮-订单拒绝退款
    public function reject_refund(Request $request)
    {
        $order_id = (int)$request->input("order_id", 0);

        return view('cater.orders.reject_refund', ['order_id' => $order_id]);
    }

}
