<?php
/**
 * Date: 2018/7/23
 * Time: 14:52
 * 腾讯地图类
 */

namespace App\Librarys;

use DB;

class Location
{

    private static $url = "https://apis.map.qq.com";
    private static $key = "SNABZ-UJJKX-CRQ4E-ZG7Q6-57FTK-NFBGX";

    /**
     * 地理位置计算距离
     * @param $admin_id 商家ID
     * @param $latitude 纬度
     * @param $longitude 经度
     * @return array
     */
    public static function getLocation($admin_id, $from_latitude, $from_longitude)
    {
        $shop_info = DB::table("cater_shop")->where(['admin_id' => $admin_id, 'isvalid' => true])->select(['longitude', 'latitude'])->first();

        $result = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => []
        );

        if ($shop_info) {
            $to_longitude = $shop_info->longitude;
            $to_latitude = $shop_info->latitude;

            if ($to_longitude && $to_latitude) {
                $url = self::$url . "/ws/distance/v1/?mode=driving&from={$from_latitude},{$from_longitude}&to={$to_latitude},{$to_longitude}&key=" . self::$key;

                $output = self::curl_https($url, "", 0);

                $output = json_decode($output, true);

                if ($output['status'] == 0) {
                    $result['errcode'] = 1;
                    $result['errmsg'] = "成功";
                    $result['data'] = $output['result']['elements'];
                }

            }
        } else {
            $result['errmsg'] = "店铺还没设置";
        }

        return $result;
    }

    /**
     * 地理位置解析地址坐标
     * @param $address 详细地址
     * @return array
     */
    public static function declareAddress($address)
    {

        $result = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => []
        );

        $url = "http://apis.map.qq.com/ws/geocoder/v1/?address=" . $address . "&key=" . self::$key;

        $output = self::curl_https($url, "", 0);

        $output = json_decode($output, true);

        if ($output['status'] == 0) {
            $result['errcode'] = 1;
            $result['errmsg'] = "成功";
            $result['data']['longitude'] = $output['result']['location']['lng'];
            $result['data']['latitude'] = $output['result']['location']['lat'];
        }

        return $result;
    }

    /**
     * curl
     */
    public static function curl_https($url, $data = array(), $is_post = 1, $timeout = 30, $debug = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if ($is_post == 1) {
            curl_setopt($ch, CURLOPT_POST, true);
        }

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $response = curl_exec($ch);

        if ($error = curl_error($ch)) {
            die($error);
        }
        curl_close($ch);

        return $response;

    }
}