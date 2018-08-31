<?php
/**
 * User: 35727
 * Date: 2018/7/20
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Librarys\Location;
use App\Librarys\Sms;
use Illuminate\Support\Facades\Crypt;
use DB;

class getShopController extends Controller
{
    /**
     * 获取店铺信息
     * @param Request $request
     * @return string
     */
    public function getShopInfo(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $latitude = $request->input("latitude", "");
        $longitude = $request->input("longitude", "");

        //获取店铺信息
        $shop_info = DB::table("cater_shop")->where(["admin_id" => $admin_id, "isvalid" => true])->first();

        if ($shop_info) {
            $shop_info->provinve = DB::table("address")->whereId($shop_info->province_id)->value("name");
            $shop_info->city = DB::table("address")->whereId($shop_info->city_id)->value("name");
            $shop_info->area = DB::table("address")->whereId($shop_info->area_id)->value("name");

            $shop_info->package_fee = round((float)$shop_info->package_fee, 2);
            $shop_info->shipping_fee = round((float)$shop_info->shipping_fee, 2);
            $shop_info->delivery_fee = round((float)$shop_info->delivery_fee, 2);
            //获取店铺展示图片
            $shop_info->figure_img = DB::table("cater_figure_img")->where(["admin_id" => $admin_id, "isvalid" => true, 'foreign_id' => $shop_info->id, 'type' => 1])->get();

            if ($latitude && $longitude) {
                //计算距离
                $location_info = Location::getLocation($admin_id, $latitude, $longitude);

                if ($location_info['errcode'] == 1) { //成功
                    $distance = (int)$location_info['data'][0]['distance'];

                    if ($distance > 10000) {
                        $shop_info->distance = ">10km";
                    } elseif ($distance <= 10000 && $distance > 1000) {
                        $shop_info->distance = round($distance / 1000, 2) . "km";
                    } else {
                        $shop_info->distance = $distance . "m";
                    }
                } else {
                    $shop_info->distance = 0;
                }
            }
        }

        return json_encode($shop_info);

    }

    /**
     * 获取首页展示图
     * @param Request $request
     * @return string
     */
    public function getHomeImg(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);

        $shop_info = DB::table("cater_figure_img")->where(["admin_id" => $admin_id, "isvalid" => true, "type" => 3])->get();

        return json_encode($shop_info);

    }

    /**
     * 验证码
     * @param Request $request
     * @return string
     */
    public function getCode(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $phone = $request->input("phone", "");
        $code = rand(pow(10, 5), pow(10, 6) - 1);

        \Cache::put('code', $code, 3);  //添加缓存,有效期3分钟

        //发送短信
        $result = Sms::sendSms($admin_id, 1, $code, $phone);

        return json_encode($result);

    }

    /**
     * 修改支付密码
     * @param Request $request
     * @return string
     */
    public function savePassword(Request $request)
    {
        $user_id = (int)$request->input("user_id", 0);
        $old_currency_password = $request->input("old_currency_password", "");
        $phone = $request->input("phone", "");
        $currency_password = $request->input("currency_password", "");
        $code = $request->input("code", "");
        $change_type = $request->input("change_type", 0);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败"
        );

        if($change_type == 1){ //密码
            //判断原密码是否正确
            if (!empty($old_currency_password)) {
                $ori_old_currency_password = DB::table("cater_users")->whereId($user_id)->value("currency_password");

                if (Crypt::decrypt($ori_old_currency_password) != $old_currency_password) {
                    $return['errmsg'] = "原密码错误，请重新输入";

                    return json_encode($return);
                }else{
                    $result = DB::table("cater_users")->whereId($user_id)->update(['currency_password' => Crypt::encrypt($currency_password)]);

                    if ($result) {
                        $return['errcode'] = 1;
                        $return['errmsg'] = "设置成功";
                    }
                }
            }
        }else{//验证码
            if (\Cache::has('code')) {
                $old_code = \Cache::get("code");

                if ($code == $old_code) {
                    //修改用户支付密码和手机号
                    $result = DB::table("cater_users")->whereId($user_id)->update(['currency_password' => Crypt::encrypt($currency_password), 'mobile' => $phone]);

                    if ($result) {
                        $return['errcode'] = 1;
                        $return['errmsg'] = "设置成功";
                    }
                } else {
                    $return['errmsg'] = "验证码错误";
                }
            } else {
                $return['errmsg'] = "验证码已过期";
            }
        }

        return json_encode($return);
    }
}