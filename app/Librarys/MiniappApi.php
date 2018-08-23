<?php
/**
 * Date: 2018/8/6
 * Time: 14:52
 * 小程序接口
 */

namespace App\Librarys;

use DB;

class MiniappApi
{
    /**
     * 生成小程序二维码
     * @return array
     */
    public static function createQrCode($admin_id, $upload_path)
    {
        //获取小程序配置
        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "path" => ""
        );
        $system_info = DB::table("cater_system")->where(['admin_id' => $admin_id, 'isvalid' => true])->first();

        if (!$system_info) {
            $return['errmsg'] = "请先配置小程序信息";

            return $return;
        }

        $acc_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $system_info->appid . "&secret=" . $system_info->appsecret;

        $acc_output = self::curl_https($acc_url, '');

        $acc_output = json_decode($acc_output, true);

        $access_token = $acc_output['access_token'];

        if ($access_token) { //生成二维码
            $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=$access_token";

            $data = array(
                "path" => "pages/ordering/ordering?admin_id=" . $admin_id . "&cater_type=1",
                "width" => 200,

            );

            $output = self::curl_https($url, json_encode($data));

            if ($output) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";

                $uplaod_path = "/upload/" . $admin_id . $upload_path;
                $path = public_path($uplaod_path);

                $file_name = date("Ymd") . mt_rand(1, 99999) . ".png";

                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }

                file_put_contents($path . "/" . $file_name, $output);

                $return['path'] = $uplaod_path . "/" . $file_name;
            }
        }

        return $return;
    }

    /**
     * 发送模板消息
     * @return array
     */
    public static function sendTemplate($admin_id, $data)
    {
        //获取小程序配置
        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "path" => ""
        );
        $system_info = DB::table("cater_system")->where(['admin_id' => $admin_id, 'isvalid' => true])->first();

        if (!$system_info) {
            $return['errmsg'] = "请先配置小程序信息";

            return $return;
        }

        $acc_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $system_info->appid . "&secret=" . $system_info->appsecret;

        $acc_output = self::curl_https($acc_url, '');

        $acc_output = json_decode($acc_output, true);

        $access_token = $acc_output['access_token'];

        if ($access_token) { //生成二维码
            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=$access_token";

            $output = self::curl_https($url, $data);

            $output = json_decode($output, true);

            if ($output['errcode'] == 0) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
            } else {
                $return['errmsg'] = $output['errmsg'];
            }
        }

        return $return;
    }

    /**
     * curl
     */
    public static function curl_https($url, $data, $second = 30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if ($data) {

            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
            //return $this->setError("curl出错，错误码:$error");
        }
    }
}