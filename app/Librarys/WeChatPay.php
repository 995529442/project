<?php

namespace App\Librarys;

use DB;

class WeChatPay
{
    private $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    private $appid = '';

    private $mch_id = '';

    private $error = '';

    private $notify_url = '';

    private $key = '';

    public function getConfig($admin_id)
    {
        $config_info = DB::table("cater_system")->where(['admin_id' => $admin_id, 'isvalid' => true])->first();

        if ($config_info) {
            $this->appid = $config_info->appid;
            $this->mch_id = $config_info->mch_id;
            $this->notify_url = '';
            $this->key = $config_info->key;

            return $config_info;
        } else {
            return false;
        }

    }

    private function setError($string)
    {
        $this->error = $string;
        return $this->error;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 统一下单，返回小程序支付参数
     * @param $orderId
     * @return bool|mixed
     */
    public function getPayParams($admin_id, $orderId)
    {
        $order_info = DB::table("cater_orders")->whereId($orderId)->first();

        if (!$order_info) {
            return $this->setError("订单不存在");
        }

        $config_info = $this->getConfig($admin_id);

        if (!$config_info) {
            return $this->setError("小程序还没配置");
        }
        $data['appid'] = $this->appid;
        $data['mch_id'] = $this->mch_id;
        $data['nonce_str'] = $this->nonceStr(32);
        $data['sign_type'] = 'MD5';
        $data['body'] = '餐饮';
        $data['out_trade_no'] = $order_info->batchcode;
        $data['total_fee'] = $order_info->real_pay * 100;
        //$data['spbill_create_ip'] = IClient::getIP();
        $data['notify_url'] = $this->notify_url;
        $data['trade_type'] = 'JSAPI';
        //openid
        // $userModel = new IModel('member');
        // $user = $userModel->getObj("user_id = $order[user_id]");
        $openId = DB::table("cater_users")->whereId((int)$order_info->user_id)->value("openid");

        if (!$openId) {
            return $this->setError("用户信息不存在");
        }

        $data['openid'] = $openId;
        $data['sign'] = $this->sign($data);
        $result = $this->postXmlCurl($this->arrayToXml($data));
        if ($result === false) {
            return false;
        }
        $result = $this->xmlToArray($result);
        if ($result['return_code'] == 'FAIL') {
            return $this->setError($result['return_msg']);
        }
        if ($result['result_code'] == 'FAIL') {
            return $this->setError($result['err_code_des']);
        }
        $prepay_id = $result['prepay_id'];
        return $this->getJsPayParams($prepay_id, $orderId);
    }

    private function getJsPayParams($prepay_id, $orderId)
    {
//        appId,nonceStr,package,signType,timeStamp
        $data['appId'] = $this->appid;
        $data['nonceStr'] = $this->nonceStr(32);
        $data['package'] = 'prepay_id=' . $prepay_id;
        $data['signType'] = 'MD5';
        $data['timeStamp'] = time();
        $data['sign'] = $this->sign($data);
        $data['orderId'] = $orderId;
        return $data;
    }

    public function notifySign($data)
    {
        if (empty($data)) {
            return $this->setError('签名为空');
        }
        $sign = $data['sign'];
        unset($data['sign']);
        if ($sign !== $this->sign($data)) {
            return $this->setError('签名失败');
        } else {
            return true;
        }
    }

    private function nonceStr($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function sign($data)
    {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        ksort($data);
        $string = http_build_query($data);
        $string = urldecode($string);
        $string .= '&key=' . $this->key;
        return strtoupper(MD5($string));
    }

    /**
     *  作用：array转xml
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";

            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     *  作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     *  作用：以post方式提交xml到对应的接口url
     */
    private function postXmlCurl($xml, $second = 30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if ($data) {

            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $this->setError("curl出错，错误码:$error");
        }
    }

    //微信退款
    /*
     * @$out_trade_no 单号
     * 金额
     * 总金额
     */
    public function refund($out_trade_no, $amount, $actAmount)
    {
        $nonce_str = $this->nonceStr(32);
        $refundFee = $amount; // 退款金额 退款金额小于等于订单金额
        $totalFee = $actAmount; // 订单总金额
        $sign = $this->refundOrderSign($this->appid, $this->mch_id, $nonce_str, $this->mch_id, $nonce_str, $out_trade_no, $refundFee, $totalFee);
        $data['appid'] = $this->appid;
        $data['mch_id'] = $this->mch_id;
        $data['nonce_str'] = $nonce_str;
        $data['op_user_id'] = $this->mch_id;
        $data['out_refund_no'] = $nonce_str;
        $data['out_trade_no'] = $out_trade_no;
        $data['refund_fee'] = $refundFee;
        $data['total_fee'] = $totalFee;
        $data['sign'] = $sign;
        $urlStr = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $result = $this->curl_post_ssl($urlStr, $this->arrayToXml($data), 30);
        if ($result === false) {
            return false;
        }
        $result = $this->xmlToArray($result);
        if ($result['return_code'] == 'FAIL') {
            return $this->setError($result['return_msg']);
        }
        if ($result['result_code'] == 'FAIL') {
            return $this->setError($result['err_code_des']);
        }
        //记录微信接口调用信息
        $mongoDb = new IMongo();
        $mongoDb->insert("log_operation", array('author' => "管理员:" . ISafe::get('admin_name'), 'action' => "调用微信退款接口", 'content' => '订单号：' . $out_trade_no . "；退款金额：" . ($refundFee / 100) . "；IP：" . IClient::getIp(), 'datetime' => date('Y-m-d H:i:s')));
        return true;
    }

    // 订单退款 签名算法
    function refundOrderSign($appid, $mch_id, $nonce_str, $op_user_id, $out_refund_no, $out_trade_no, $refund_fee, $total_fee)
    {
        $ret = array('appid' => $appid,
            'mch_id' => $mch_id,
            'nonce_str' => $nonce_str,
            'op_user_id' => $op_user_id,
            'out_refund_no' => $out_refund_no,
            'out_trade_no' => $out_trade_no,
            'refund_fee' => $refund_fee,
            'total_fee' => $total_fee
        );
        return $this->sign($ret);
    }

    /*
请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
*/
    function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //以下两种方式需选择一种
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        // curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        //  curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert.pem');
        //默认格式为PEM，可以注释
        // curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        // curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/private.pem');

        //第二种方式，两个文件合成一个.pem文件
        // curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
        curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . DIRECTORY_SEPARATOR .
            'cert' . DIRECTORY_SEPARATOR . 'apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . DIRECTORY_SEPARATOR .
            'cert' . DIRECTORY_SEPARATOR . 'apiclient_key.pem');
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . 'rootca.pem');

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }


}