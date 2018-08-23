<?php
/**
 * Date: 2018/7/18
 * Time: 14:52
 * 发送短信
 */

namespace App\Librarys;

date_default_timezone_set('PRC');
use DB;

class Sms
{
    /**
     * 发送短信
     * @param $admin_id 商家ID
     * @param $type 类型
     * @param $param 发件内容
     * @param $mobile 手机号码
     * @return array
     */
    public static function sendSms($admin_id, $type, $param, $mobile)
    {
        //载入ucpass类
        require_once('lib/phpsms/Ucpaas.class.php');

        $result = array(
            "errcode" => -1,
            "errmsg" => "发送失败"
        );
        //获取配置
        if ($admin_id) {
            $sms_info = DB::table("sms")->where("admin_id", $admin_id)->first();

            if ($sms_info) {

                //查找模板ID
                $tem_info = DB::table("sms_template")->where(["admin_id" => $admin_id, "type" => $type])->first();

                if (!$tem_info) {
                    $result['errmsg'] = "模板ID不存在";
                    return $result;
                } else {
                    $templateid = $tem_info->template_id;
                }
                //初始化必填
                //填写在开发者控制台首页上的Account Sid
                $options['accountsid'] = $sms_info->accountsid;
                //填写在开发者控制台首页上的Auth Token
                $options['token'] = $sms_info->token;

                //初始化 $options必填
                $ucpass = new \Ucpaas($options);

                $appid = $sms_info->appid;    //应用的ID，可在开发者控制台内的短信产品下查看

                //70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

                $response = $ucpass->SendSms($appid, $templateid, $param, $mobile, "");

                $send_result = json_decode($response, true);

                $is_success = 0;
                $content = "";
                if ($send_result['code'] == "000000" && $send_result['msg'] == "OK") { //发送成功
                    $is_success = 1;
                    $remark = "发送成功";
                    $send_time = strtotime($send_result['create_date']); //发送时间

                    if ($type == 1) {
                        $content = "本次验证码为" . $param . "，请于3分钟内正确输入，切勿泄露他人。";
                    } elseif ($type == 2) {
                        $content = "新订单通知：您有一笔新订单" . $param . "，请及时登录处理。";
                    }

                    $result['errcode'] = 1;
                    $result['errmsg'] = "发送成功";
                } else {
                    $content = $send_result['msg'];
                    $remark = "发送失败";
                    $send_time = time();

                    $result['errmsg'] = $send_result['msg'];
                }

                //记录日志
                DB::table("send_log")->insert([
                    'admin_id' => $admin_id,
                    'send_to' => $mobile,
                    'content' => $content,
                    'is_success' => $is_success,
                    'remark' => $remark,
                    'send_time' => $send_time
                ]);

                return $result;
            } else {
                $result['errmsg'] = "该商家还没设置短信配置";
                return $result;
            }
        } else {
            $result['errmsg'] = "无效商家";
            return $result;
        }
    }
}