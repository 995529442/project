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
use Illuminate\Support\Facades\Crypt;
use App\Librarys\MiniappApi;
use App\Librarys\Sms;
use App\Librarys\Mail;
use DB;

class orderController extends Controller
{
    /**
     * 获取我的订单
     * @param Request $request
     * @return string
     */
    public function getOrders(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);
        $page = (int)$request->input("page", 0);
        $type = (int)$request->input("type", 0);

        if ($type == 0) {
            $type = 1;
        } elseif ($type == 1) {
            $type = 2;
        }

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => []
        );

        $order_list = DB::table("cater_orders")->where(['admin_id' => $admin_id, 'user_id' => $user_id, 'isvalid' => true, 'type' => $type])
            ->selectRaw('id as order_id,batchcode,pay_type,status,total_money,total_num,FROM_UNIXTIME(create_time) as create_time')
            ->orderByDesc("id")
            ->offset(($page - 1) * 4)->limit(4)->get();

        if ($order_list) {
            foreach ($order_list as $k => $v) {
                $goods_list = DB::table("cater_orders_goods as og")
                    ->leftJoin("cater_goods as cg", "cg.id", "=", "og.goods_id")
                    ->select(['og.good_name', 'og.price', 'og.number', 'og.total_price', 'cg.thumb_img'])
                    ->where(['og.order_id' => (int)$v->order_id])
                    ->get();

                $order_list[$k]->goods_list = $goods_list;
            }
            $return['errcode'] = 1;
            $return['errmsg'] = "成功";
            $return['data'] = $order_list;
        } else {
            $return['errmsg'] = "数据为空";
        }

        return json_encode($return);
    }

    /**
     * 结算
     * @param Request $request
     * @return string
     */
    public function checkSubmit(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $goods_id_arr = $request->input("goods_id_arr", '');
        $cater_type = (int)$request->input("cater_type", 0); //类型，1堂食 2外卖 3排队

        //查找店铺信息
        $return = array(
            "errcode" => 1,
            "errmsg" => '成功'
        );

        $shop_info = DB::table("cater_shop")->where(['admin_id' => $admin_id, 'isvalid' => true])->first();

        if ((int)$shop_info->status == 2) {
            $return['errcode'] = -1;
            $return['errmsg'] = "商家已打烊";

            return json_encode($return);
        }

        if (strtotime(date("Y-m-d") . " " . $shop_info->begin_time) > time()) {
            $return['errcode'] = -1;
            $return['errmsg'] = "还没到营业时间";

            return json_encode($return);
        }

        if (strtotime(date("Y-m-d") . " " . $shop_info->end_time) < time()) {
            $return['errcode'] = -1;
            $return['errmsg'] = "商家已打烊";

            return json_encode($return);
        }

        if ((int)$shop_info->is_eat_in == 1 && $cater_type == 1) {
            $return['errcode'] = -1;
            $return['errmsg'] = "商家未开启堂食";

            return json_encode($return);
        }

        if ((int)$shop_info->is_take_out == 1 && $cater_type == 2) {
            $return['errcode'] = -1;
            $return['errmsg'] = "商家未开启外卖";

            return json_encode($return);
        }

        $total_money = 0;  //总价钱
        if ($goods_id_arr) {
            $goods_id_arr = json_decode($goods_id_arr, true);

            foreach ($goods_id_arr as $k => $v) {
                $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

                if ($goods_info->isout == 1) {
                    $return['errcode'] = -1;
                    $return['errmsg'] = $goods_info->good_name . "还没上架";

                    return json_encode($return);
                    break;
                }

                if ($goods_info->storenum < $v['number']) {
                    $return['errcode'] = -1;
                    $return['errmsg'] = $goods_info->good_name . "库存不足，最多能选" . $goods_info->storenum . "份";

                    return json_encode($return);
                    break;
                }

                $total_money += $goods_info->now_price * $v['number'];
            }
        } else {
            $return['errcode'] = -1;
            $return['errmsg'] = "您还没有点餐";

            return json_encode($return);
        }

        if ($total_money < $shop_info->delivery_fee && $cater_type == 2) {
            $return['errcode'] = -1;
            $return['errmsg'] = "起送价为" . $shop_info->delivery_fee . "元";

            return json_encode($return);
        }

        return json_encode($return);
    }

    /**
     * 下单
     * @param Request $request
     * @return string
     */
    public function pay(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);
        $goods_id_arr = $request->input("goods_id_arr", '');
        $user_name = $request->input("user_name", '');
        $phone = $request->input("phone", '');
        $cater_type = (int)$request->input("cater_type", 0); //类型，1堂食 2外卖 3排队
        $remark = $request->input("remark", '') == null ? '' : $request->input("remark", '');   //留言
        $formId = $request->input("formId", '') == null ? '' : $request->input("formId", '');
        $currency_password = $request->input("currency_password", '') == null ? '' : $request->input("currency_password", '');
        $payment_type = (int)$request->input("pay_type", 0); //支付方式 0微信支付1购物币支付

        $return = array(
            "errcode" => -1,
            "errmsg" => '失败'
        );
        try {
            DB::beginTransaction();

            //获取用户openid
            $openid = DB::table("cater_users")->where(['id' => $user_id, 'admin_id' => $admin_id, 'isvalid' => true])->value("openid");

            if (!$openid) {
                $return['errmsg'] = '用户不存在';

                return json_encode($return);
            }

            if ($payment_type == 1) {  //购物币支付
                if (!empty($currency_password)) {
                    //判断密码是否或者正确
                    $user_currency_password = DB::table("cater_users")->whereId($user_id)->value("currency_password");

                    if (!empty($user_currency_password)) {

                        if ($currency_password != Crypt::decrypt($user_currency_password)) {
                            $return['errmsg'] = '支付密码错误';

                            return json_encode($return);
                        }
                    } else {
                        $return['errcode'] = -2;
                        $return['errmsg'] = '您还没有设置密码';

                        return json_encode($return);
                    }
                } else {
                    $return['errmsg'] = '支付密码不能为空';

                    return json_encode($return);
                }
            }
            //生成订单号
            $batchcode = date('Ymd') . substr(time(), 5) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

            //获取店铺额外费用
            $shop_info = DB::table("cater_shop")->where(['admin_id' => $admin_id, 'isvalid' => true]);

            $shipping_fee = $cater_type == 2 ? $shop_info->value("shipping_fee") : 0;
            $package_fee = $cater_type == 2 ? $shop_info->value("package_fee") : 0;
            $shop_name = $shop_info->value("name");
            $shop_phone = $shop_info->value("phone");
            $is_open_sms = $shop_info->value("is_open_sms");
            $is_open_mail = $shop_info->value("is_open_mail");
            $shop_mail = $shop_info->value("shop_mail");
            $goods_name_str = "";  //商品名称列表，用于发送模板
            $goods_name_mail_str = "";  //商品名称列表，用于发送邮件

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
                "create_time" => time(),
                "payment_type" => $payment_type,
                "isvalid" => true
            );

            $order_id = DB::table("cater_orders")->insertGetId($order_data);

            if ($order_id) {
                $total_money = 0;  //总金额
                $total_num = 0;    //总数量
                if ($goods_id_arr) {
                    $goods_id_arr = json_decode($goods_id_arr, true);

                    $order_goods_arr = array();
                    foreach ($goods_id_arr as $k => $v) {
                        $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

                        //判断库存是否足够
                        if ($goods_info->storenum < (int)$v['number']) {
                            $return['errmsg'] = $goods_info->good_name . '库存不足，最多能选' . $goods_info->storenum . "件，请重新选择再下单";

                            return json_encode($return);

                            break;
                        }

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

                        $goods_name_str .= $goods_info->good_name . $v['number'] . "份" . $money . "元\\n";
                        $goods_name_mail_str .= "商品名称：</span>" . $goods_info->good_name . $v['number'] . "份  " . $money . "元<br /><span style='visibility:hidden;'>";
                    }

                    $goods_name_str = trim($goods_name_str, "\\n");
                    $goods_name_mail_str = trim($goods_name_mail_str, "<br /><span style='visibility:hidden;'>");
                }
                //真实支付金额，菜品总额+配送+包装
                $real_pay = $total_money + $shipping_fee + $package_fee;

                $order_model = DB::table("cater_orders")->whereId($order_id);
                $order_model->update(['real_pay' => round($real_pay, 2), 'total_money' => round($total_money, 2), 'total_num' => $total_num]);

                DB::table("cater_orders_goods")->insert($order_goods_arr);

                if ($payment_type == 1) {
                    $user_model = DB::table("cater_users")->whereId($user_id);
                    $currency_money = $user_model->value("currency_money");

                    if ($currency_money < $real_pay) {
                        $return['errmsg'] = '购物币余额不足以支付';

                        return json_encode($return);
                    } else {  //购物币支付
                        $pay_result = $user_model->decrement("currency_money", $real_pay);

                        $order_model->update(['pay_type' => 1, 'status' => 1, 'pay_time' => time()]);

                        if ($pay_result) {
                            DB::table("cater_currency_log")->insert([
                                "admin_id" => $admin_id,
                                "operate_from" => $user_name,
                                "user_id" => $user_id,
                                "operate_to" => $user_name,
                                "remark" => "订单支付，扣减" . $real_pay . "元，订单号:" . $batchcode,
                                "create_time" => time(),
                                "type" => 2,
                                "currency_money" => $real_pay,
                                "isvalid" => true
                            ]);

                            foreach ($goods_id_arr as $k => $v) {
                                $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

                                $order_goods_model = DB::table("cater_goods")->whereId($goods_info->id);
                                $order_goods_model->decrement('storenum', (int)$v['number']);    //减库存
                                $order_goods_model->increment('sell_count', (int)$v['number']);  //增加销量
                            }

                            //用户总订单加一
                            DB::table("cater_users")->whereId($user_id)->increment('order_num', 1);
                        }
                    }
                }

                //记录form_id
                // if($formId){
                //    DB::table("cater_form")->insert(array([
                //        "admin_id" => $admin_id,
                //        "user_id" => $user_id,
                //        "form_id" => $formId,
                //        "isvalid" => true
                //    ]));
                // }

                if ($cater_type == 1) {
                    $cater_type_name = "堂食";
                } else {
                    $cater_type_name = "外卖";
                }

                if ($payment_type == 0) {
                    $payment_type_show = "微信支付";
                } else {
                    $payment_type_show = "购物币支付";
                }
                if ($is_open_sms == 1) {  //商家开启了短信通知
                    $param = "$batchcode,$real_pay,$user_name,$phone";
                    Sms::sendSms($admin_id, 2, $param, $shop_phone);
                }
                if ($is_open_mail == 1 && !empty($shop_mail)) {  //商家开启了短信通知
                    $content = "您有一笔新的订单，请及时处理！！！<br />";
                    $content .= "订单编号：" . $batchcode . "<br />";
                    $content .= "订单类型：" . $cater_type_name . "<br />";
                    $content .= $goods_name_mail_str . "<br />";
                    $content .= "支付方式：" . $payment_type_show . "<br />";
                    $content .= "支付金额：" . $real_pay . "元<br />";
                    $content .= "支付时间：" . date("Y-m-d H:i:s", time()) . "<br />";
                    $content .= "备注：" . $remark . "<br />";
                    Mail::sendMail($admin_id, $shop_mail, "新订单通知", $content);
                }
                //發送模板消息
                $template_id = DB::table("cater_template")->where(['admin_id' => $admin_id, 'isvalid' => true, 'type' => 1, 'is_on' => 1])->value("template_id");

                if ($template_id && $formId) {
                    $data = '{
                    "touser": "' . $openid . '",
                    "template_id": "' . $template_id . '",
                    "form_id": "' . $formId . '",
                    "data": {
                        "keyword1": {
                            "value": "' . $shop_name . '"
                        },
                        "keyword2": {
                            "value": "' . $batchcode . '"
                        },
                        "keyword3": {
                            "value": "' . $cater_type_name . '"
                        } ,
                        "keyword4": {
                            "value": "' . $goods_name_str . '"
                        },
                        "keyword5": {
                            "value": "' . $payment_type_show . '"
                        },
                        "keyword6": {
                            "value": "￥' . $real_pay . '元"
                        },
                        "keyword7": {
                            "value": "' . date("Y-m-d H:i:s", time()) . '"
                        },
                        "keyword8": {
                            "value": "如有疑问请致电' . $shop_phone . '"
                        }
                    }
                  }';

                    $result = MiniappApi::sendTemplate($admin_id, $data);

                }

                DB::commit();

                $return['errocde'] = 1;
                $return['errmsg'] = "成功";

                return json_encode($return);
            }
        } catch (\Exception $exception) {
            DB::rollback();//事务回滚
            throw $exception;
        }

    }

    /**
     * 订单处理
     * @param Request $request
     * @return string
     */
    public function operate(Request $request)
    {
        $order_id = (int)$request->input("order_id", 0);
        $type = $request->input("type", "");

        $return = array(
            "errcode" => -1,
            "errmsg" => '失败'
        );

        if ($order_id > 0) {
            $order_model = DB::table("cater_orders")->whereId($order_id);
            switch ($type) {
                case 'refund':
                    $result = $order_model->update(['status' => 6]);
                    break;
                case 'done':
                    try {
                        DB::beginTransaction();

                        $result = $order_model->update(['status' => 5, 'shipping_con_time' => time(), 'confirm_time' => time()]);

                        //更新用户数据
                        $user_id = $order_model->first()->user_id;
                        $real_pay = $order_model->first()->real_pay;

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
                case 'cancel':
                    $result = $order_model->update(['status' => -1, 'recovery_time' => time()]);
                    break;
                default:
                    # code...
                    break;
            }

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
            }
        } else {
            $return['errmsg'] = "系统错误";
        }

        return json_encode($return);
    }
}