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
use App\Librarys\Location;
use DB;

class getUserInfoController extends Controller
{
    /**
     * 获取用户信息
     * @param Request $request
     * @return string
     */
    public function getUsers(Request $request)
    {
        require_once(public_path('/wx/wxBizDataCrypt.php'));   //引入解密文件

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败"
        );
        $admin_id = (int)$request->input("admin_id", 0);
        $code = $request->input("code", "");
        $iv = $request->input("iv", "");
        $encrypted_data = $request->input("encrypted_data", "");

        if (!$code) {
            $return['errmsg'] = "code不能为空";
            return json_encode($return);
        }

        //查找APPID和SECRET
        $system_info = DB::table("cater_system")->select(['appid', 'appsecret'])->where(["admin_id" => $admin_id, "isvalid" => true])->first();

        if (!$system_info) {
            $return['errmsg'] = "商家还没设置小程序配置信息";
            return json_encode($return);
        } else {
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $system_info->appid . "&secret=" . $system_info->appsecret . "&js_code=" . $code . "&grant_type=authorization_code";

            $output = $this->curl_https($url, "", 0);

            $result = json_decode($output, true);

            if (isset($result['session_key'])) {
                $pc = new \WXBizDataCrypt($system_info->appid, $result['session_key']);
                $errCode = $pc->decryptData($encrypted_data, $iv, $data);

                if ($errCode != 0) {
                    $return['errmsg'] = "解密失败";
                    return json_encode($return);
                } else {
                    $user_info = json_decode($data, true);

                    //保存用户数据
                    $user = DB::table("cater_users")->where(['admin_id' => $admin_id, 'openid' => $user_info['openId'], 'isvalid' => true])->first();

                    $insert_data = array(
                        "weixin_name" => $user_info['nickName'],
                        "province" => $user_info['province'],
                        "city" => $user_info['city'],
                        "openid" => $user_info['openId'],
                        "unionid" => isset($user_info['unionId']) ? $user_info['unionId'] : "",
                        "headimgurl" => $user_info['avatarUrl'],
                        "sex" => $user_info['gender']
                    );
                    if (!$user) { //插入数据
                        $insert_data['admin_id'] = $admin_id;
                        $insert_data['isvalid'] = true;

                        $user_id = DB::table("cater_users")->insertGetId($insert_data);
                    } else {  //更新用户数据
                        $user_id = $user->id;
                        DB::table("cater_users")->whereId($user->id)->update($insert_data);
                    }

                    $user_info['user_id'] = $user_id;
                    return $user_info;
                }

            } else {
                $return['errmsg'] = "code参数失效";
                return json_encode($return);
            }
        }

    }

    /**
     * 获取用户地址
     * @param Request $request
     * @return string
     */
    public function getAddress(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);
        $pay_type = (int)$request->input("pay_type", 0);  //为1则为下单过来，需要计算距离是否在配送范围内
        $page = (int)$request->input("page", 1);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => []
        );

        if ($pay_type == 1) { //外卖，店铺配送距离
            $delivery_km = DB::table("cater_shop")->where(['admin_id' => $admin_id, 'isvalid' => true])->value("delivery_km");
        }
        if ($admin_id && $user_id) {
            $address_list = DB::table("cater_user_shipping")->where(['admin_id' => $admin_id, 'user_id' => $user_id, 'isvalid' => true])
                ->orderByDesc("is_default")
                ->orderByDesc("id")
                ->offset(($page - 1) * 12)->limit(12)->get();

            if ($address_list && $pay_type == 1) {
                foreach ($address_list as $k => $v) {
                    //腾讯地图解析地址坐标
                    $detail_address = $v->province . $v->city . $v->country . $v->address;
                    $result = Location::declareAddress($detail_address);

                    if ($result['errcode'] == 1) {
                        $longitude = $result['data']['longitude'];
                        $latitude = $result['data']['latitude'];

                        //计算距离
                        $location_info = Location::getLocation($admin_id, $latitude, $longitude);

                        if ($location_info['errcode'] == 1) { //成功
                            $distance = (int)$location_info['data'][0]['distance'];

                            if ($delivery_km * 1000 < $distance) {
                                $address_list[$k]->is_out = 1;
                            } else {
                                $address_list[$k]->is_out = 0;
                            }
                        } else {
                            $address_list[$k]->is_out = 1;
                        }
                    } else {
                        $address_list[$k]->is_out = 1;
                    }
                }
            }

            $return['errcode'] = 1;
            $return['errmsg'] = "成功";
            $return['data'] = $address_list;
        } else {
            $return['errmsg'] = "系统错误";
        }

        return json_encode($return);
    }

    /**
     * 新增用户地址
     * @param Request $request
     * @return string
     */
    public function addAddress(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);
        $address_id = (int)$request->input("address_id", 0);
        $user_name = $request->input("user_name", "");
        $phone = $request->input("phone", "");
        $province = $request->input("province", "");
        $city = $request->input("city", "");
        $country = $request->input("country", "");
        $address = $request->input("address", "");
        $house_number = $request->input("house_number", "");
        $is_default = $request->input("is_default", 0);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => ""
        );
        if ($admin_id && $user_id) {
            $data = array(
                "province" => $province,
                "city" => $city,
                "country" => $country,
                "address" => $address,
                "house_number" => $house_number,
                "user_name" => $user_name,
                "phone" => $phone,
                "is_default" => $is_default
            );

            try {
                DB::beginTransaction();

                if ($is_default) { //设置默认地址先把其他重置
                    DB::table("cater_user_shipping")->where(['admin_id' => $admin_id, 'user_id' => $user_id, 'isvalid' => true])->update(['is_default' => 0]);
                }
                if ($address_id > 0) { //修改
                    $result = DB::table("cater_user_shipping")->whereId($address_id)->update($data);
                } else {  //新增
                    $data['admin_id'] = $admin_id;
                    $data['user_id'] = $user_id;
                    $data['isvalid'] = true;

                    $result = DB::table("cater_user_shipping")->insertGetId($data);
                }

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();//事务回滚
                throw $exception;
            }

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
                $return['data'] = $result;
            } else {
                $return['errmsg'] = "失败";
            }

        } else {
            $return['errmsg'] = "系统错误";
        }

        return json_encode($return);
    }

    /**
     * 删除用户地址
     * @param Request $request
     * @return string
     */
    public function delAddress(Request $request)
    {
        $address_id = (int)$request->input("address_id", 0);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败"
        );

        if ($address_id) {
            $result = DB::table("cater_user_shipping")->whereId($address_id)->update(['isvalid' => false]);

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
            } else {
                $return['errmsg'] = "失败";
            }
        } else {
            $return['errmsg'] = "系统错误";
        }

        return json_encode($return);
    }

    /**
     * 获取用户单条地址
     * @param Request $request
     * @return string
     */
    public function getOneAddress(Request $request)
    {
        $address_id = (int)$request->input("address_id", 0);

        $shipping = DB::table("cater_user_shipping")->whereId($address_id)->first();

        return json_encode($shipping);
    }

    /**
     * 获取用户默认地址
     * @param Request $request
     * @return string
     */
    public function getDefaultAddress(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);

        $return = array(
            "errcode" => 1,
            "errmsg" => "失败",
            "data" => []
        );
        $shipping = DB::table("cater_user_shipping")->where(['admin_id' => $admin_id, 'user_id' => $user_id, 'isvalid' => true, 'is_default' => 1])->first();

        //判断是否在配送范围
        if ($shipping) {
            $delivery_km = DB::table("cater_shop")->where(['admin_id' => $admin_id, 'isvalid' => true])->value("delivery_km");
            $detail_address = $shipping->province . $shipping->city . $shipping->country . $shipping->address;
            $result = Location::declareAddress($detail_address);

            if ($result['errcode'] == 1) {
                $longitude = $result['data']['longitude'];
                $latitude = $result['data']['latitude'];

                //计算距离
                $location_info = Location::getLocation($admin_id, $latitude, $longitude);

                if ($location_info['errcode'] == 1) { //成功
                    $distance = (int)$location_info['data'][0]['distance'];

                    if ($delivery_km * 1000 > $distance) {
                        $return['errcode'] = 1;
                        $return['errmsg'] = '成功';
                        $return['data'] = $shipping;
                    }
                }
            }
        }

        return $return;

    }

    /**
     * 获取用户购物币
     * @param Request $request
     * @return string
     */
    public function getMyCurrency(Request $request)
    {
        $user_id = (int)$request->input("user_id", 0);

        $user_list = DB::table("cater_users")->whereId($user_id)->select(['currency_money'])->first();

        return json_encode($user_list);
    }

    /**
     * 获取购物币消费记录
     * @param Request $request
     * @return string
     */
    public function getCurrency(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $user_id = (int)$request->input("user_id", 0);
        $page = (int)$request->input("page", 1);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败",
            "data" => []
        );

        if ($admin_id && $user_id) {
            $currency_list = DB::table("cater_currency_log")->where(['admin_id' => $admin_id, 'user_id' => $user_id, 'isvalid' => true])
                ->orderByDesc("id")
                ->offset(($page - 1) * 12)->limit(12)->get();

            if ($currency_list) {
                foreach ($currency_list as $k => $v) {
                    $currency_list[$k]->create_time = date("Y-m-d H:i:s", $v->create_time);
                }
            }
            $return['errcode'] = 1;
            $return['errmsg'] = "成功";
            $return['data'] = $currency_list;
        } else {
            $return['errmsg'] = "系统错误";
        }

        return json_encode($return);
    }

    /**
     * 获取用户是否设置支付密码
     * @param Request $request
     * @return string
     */
    public function getOneUsersSetPassword(Request $request)
    {
        $user_id = (int)$request->input("user_id", 0);

        $user_list = DB::table("cater_users")->whereId($user_id)->first();

        $is_set_password = false;
        if ($user_list) {
            if (!empty($user_list->currency_password)) {
                $is_set_password = true;
            }
        }
        return json_encode(['is_set_password' => $is_set_password]);
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