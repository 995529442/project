<?php

namespace App\Http\Controllers;

date_default_timezone_set('PRC');

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Librarys\Sms;
use DB;

class IndexController extends Controller
{
    /**
     * 后台首页
     * @return view
     */
    public function index()
    {
        $admins = Auth::guard("admins")->user();

        $username = $admins->username;  //用户名
        $type = $admins->type;  //类型：1为超级管理员 2为普通管理员
        $module = $admins->module;  //拥有的模块

        $module_arr = array();
        if (!empty($module)) {
            $module_arr = explode(",", $module);
        }

        return view("index", [
            'username' => $username,
            'type' => $type,
            'module_arr' => $module_arr
        ]);
    }

    /**
     * 后台右边主页
     * @return view
     */
    public function main()
    {
        //获取用户总数
        $user_total = DB::table("cater_users")->where(['admin_id' => Auth::guard('admins')->user()->id, 'isvalid' => true])->count();

        //订单总数
        $order_num = DB::table("cater_orders")->where(['admin_id' => Auth::guard('admins')->user()->id, 'isvalid' => true])->count();

        //已完成订单
        $order_done_num = DB::table("cater_orders")->where(['admin_id' => Auth::guard('admins')->user()->id, 'isvalid' => true, 'pay_type' => 1, 'status' => 5])->count();

        return view("main", [
            'user_total' => $user_total,
            'order_num' => $order_num,
            'order_done_num' => $order_done_num
        ]);
    }

    /**
     * 管理员页面
     * @return view
     */
    public function manage(Request $request)
    {
        $username = $request->input("username", "");

        //查找普通管理员列表
        $manage = DB::table('admins')->where(array("type" => 2, "isvalid" => true));

        if ($username) {
            $manage->where("username", "like", "%$username%");
        }

        $manage_info = $manage->orderBy("id", "desc")->get();

        if ($manage_info) {
            foreach ($manage_info as $k => $v) {
                $admin_module_name = "";
                if (!empty($v->module)) {
                    $module_arr = explode(",", $v->module);

                    for ($i = 0; $i < count($module_arr); $i++) { //查找模块名
                        $module_name = DB::table('module')->where("module_code", $module_arr[$i])->value("module_name");
                        $admin_module_name .= $module_name . ",";
                    }
                    unset($module_arr);
                }
                $manage_info[$k]->admin_module_name = rtrim($admin_module_name, ",");
                unset($admin_module_name);
            }
        }
        return view("manage", ['manage_info' => $manage_info, 'username' => $username]);
    }

    /**
     * 新增管理员页面
     * @return view
     */
    public function add_admin()
    {
        return view("add_admin");
    }

    /**
     * 保存管理员信息
     * @return view
     */
    public function save_admin(Request $request)
    {
        $username = $request->input("username", "");

        $data = array(
            "username" => $username,
            "password" => bcrypt('888888'),
            "type" => 2,
            "isvalid" => true
        );

        $admin_info = DB::table("admins")->where("username", $username)->first();

        if ($admin_info) {
            return json_encode(array("errcode" => -1, "errmsg" => "已存在系统管理员"));
        }
        $result = DB::table('admins')->insert($data);

        if ($result) {
            return json_encode(array("errcode" => 1, "errmsg" => "新增成功"));
        } else {
            return json_encode(array("errcode" => -1, "errmsg" => "新增失败"));
        }
    }

    /**
     * 重置密码
     * @return view
     */
    public function reset_admin(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", "");

        $return = array("errcode" => -1, "errmsg" => "重置失败");

        if ($admin_id > 0) {
            $result = DB::table('admins')->where(array("id" => $admin_id, "isvalid" => true))->update(array("password" => bcrypt('888888')));

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = "重置成功";
            }
        }

        return json_encode($return);
    }

    /**
     * 分配模块
     * @return view
     */
    public function module(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);

        //查找当前用户拥有的模块
        $module = DB::table("admins")->where(['id' => $admin_id, "isvalid" => true])->value("module");

        $module_arr = explode(",", $module);

        //查询模块类别
        $module = DB::table("module")->select(['id as module_id', 'module_code', 'module_name', 'is_custom'])->get();

        foreach ($module as $k => $v) {
            if (in_array($v->module_code, $module_arr)) {
                $module[$k]->is_checked = 1;
            } else {
                $module[$k]->is_checked = 0;
            }
        }

        return view('module', ['module' => $module, 'admin_id' => $admin_id]);
    }

    /**
     * 保存分配模块
     * @return view
     */
    public function saveModule(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $module = $request->input("module", "");

        if ($module) {
            $module = rtrim($module, ",");
        }

        $result = DB::table("admins")->where(['id' => $admin_id, "isvalid" => true])->update(['module' => $module]);

        if ($result) {
            return json_encode(array("errcode" => 1, "errmsg" => "成功"));
        } else {
            return json_encode(array("errcode" => -1, "errmsg" => "失败"));
        }
    }

    /**
     * 邮件页面
     * @return view
     */
    public function mail(Request $request)
    {
        //查找邮箱设置
        $mail_list = DB::table("mail")->where(['admin_id' => Auth::guard("admins")->user()->id, 'type' => 1, 'isvalid' => true])->get();

        return view('mail', ['mail_list' => $mail_list]);
    }

    /**
     * 保存邮件设置
     * @return view
     */
    public function saveMail(Request $request)
    {
        $password = $request->input("password", '');
        $mail_id = $request->input("mail_id", '');
        $name = $request->input("name", '');

        if (is_array($mail_id)) {
            $data_count = 0;

            if (is_array($name)) {
                $name_count = count($name);
            } else {
                $name_count = 0;
            }
            if (count($mail_id) >= $name_count) {
                $data_count = count($mail_id);
            } else {
                $data_count = $name_count;
            }

            for ($k = 0; $k < $data_count; $k++) {
                if (isset($mail_id[$k]) && (int)$mail_id[$k] > 0 && isset($name[$k])) { //修改
                    $update_data = array(
                        "password" => $password,
                        "name" => $name[$k]
                    );

                    $result = DB::table("mail")->whereId((int)$mail_id[$k])->update($update_data);

                } elseif (isset($mail_id[$k]) && (int)$mail_id[$k] > 0 && !isset($name[$k])) { //删除
                    $result = DB::table("mail")->whereId((int)$mail_id[$k])->update(['isvalid' => false]);
                } else {
                    $insert_data = array(
                        "admin_id" => Auth::guard("admins")->user()->id,
                        "type" => 1,
                        "password" => $password,
                        "name" => $name[$k],
                        "isvalid" => true
                    );
                    $result = DB::table("mail")->insert($insert_data);
                }
            }
        } else {
            $insert_data = array();

            for ($k = 0; $k < count($name); $k++) {
                $data = array();

                $data['admin_id'] = Auth::guard("admins")->user()->id;
                $data['type'] = 1;
                $data['password'] = $password;
                $data['name'] = $name[$k];
                $data['isvalid'] = true;

                array_push($insert_data, $data);

                unset($data);
            }

            $reuslt = DB::table("mail")->insert($insert_data);
        }

        return redirect('Index/mail');
    }

    /**
     * 短信页面
     * @return view
     */
    public function sms(Request $request)
    {
        //查找邮箱设置
        $sms_list = DB::table("sms")->where(['admin_id' => Auth::guard("admins")->user()->id, 'isvalid' => true])->first();

        return view('sms', ['sms_list' => $sms_list]);
    }

    /**
     * 保存短信设置
     * @return view
     */
    public function saveSms(Request $request)
    {
        $sms_id = (int)$request->input("sms_id", 0);
        $accountsid = $request->input("accountsid", '');
        $appid = $request->input("appid", '');
        $token = $request->input("token", '');

        $data = array(
            "accountsid" => $accountsid,
            "appid" => $appid,
            "token" => $token
        );

        if ($sms_id > 0) { //更新
            $result = DB::table("sms")->whereId($sms_id)->update($data);
        } else {
            $data['admin_id'] = Auth::guard("admins")->user()->id;
            $data['isvalid'] = true;

            $result = DB::table("sms")->insert($data);
        }

        return redirect('Index/sms');
    }

    /**
     * 短信模板
     * @return view
     */
    public function smsTemplate(Request $request)
    {
        $sms_tem_list = DB::table('sms_template')->where(['admin_id' => Auth::guard("admins")->user()->id, 'isvalid' => true])->orderBy('id', 'desc')->paginate(12);

        return view('sms_template', ['sms_tem_list' => $sms_tem_list]);
    }

    /**
     * 新增编辑短信模板
     * @return view
     */
    public function addSmsTemplate(Request $request)
    {
        $sms_template_id = (int)$request->input("sms_template_id", 0);

        $temp_info = "";

        if ($sms_template_id) {
            $temp_info = DB::table("sms_template")->whereId($sms_template_id)->first();
        }

        return view('add_sms_template', ['temp_info' => $temp_info]);
    }

    /**
     * 保存短信模板
     * @return view
     */
    public function saveSmsTemplate(Request $request)
    {
        $sms_template_id = (int)$request->input("sms_template_id", 0);
        $template_id = $request->input("template_id", '');
        $type = (int)$request->input("type", 0);
        $is_on = (int)$request->input("is_on", 0);

        $data = array(
            'template_id' => $template_id,
            'type' => $type,
            'is_on' => $is_on
        );
        if ($sms_template_id > 0) { //修改
            $result = DB::table("sms_template")->whereId($sms_template_id)->update($data);
        } else {
            $data['admin_id'] = Auth::guard("admins")->user()->id;
            $data['isvalid'] = true;

            $result = DB::table("sms_template")->insert($data);
        }

        return redirect('Index/sms_template');
    }

    /**
     * 删除短信模板
     * @return view
     */
    public function delSmsTemplate(Request $request)
    {
        $sms_template_id = (int)$request->input("sms_template_id", 0);

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败"
        );

        if ($sms_template_id > 0) {
            $result = DB::table("sms_template")->whereId($sms_template_id)->update(['isvalid' => false]);

            if ($result) {
                $return['errcode'] = 1;
                $return['errmsg'] = '成功';
            }
        } else {
            $return['errmsg'] = '系统错误';
        }

        return json_encode($return);
    }

    /**
     * 发送日志
     * @return view
     */
    public function sendLog(Request $request)
    {
        $send_log_list = DB::table('send_log')->where("admin_id", Auth::guard("admins")->user()->id)->orderBy("id", "desc")->paginate(12);

        return view('send_log', ['send_log_list' => $send_log_list]);
    }

    /**
     * 测试短信
     * @return view
     */
    public function testSms(Request $request)
    {
        $type = (int)$request->input("type", 0);
        $phone = $request->input("phone", '');

        if ($phone) {

            if ($type == 1) {
                $param = "123456";
            } else {
                $param = "123456,0.01,佚名,135XXXXXXXX";
            }
            $result = Sms::sendSms(Auth::guard("admins")->user()->id, $type, $param, $phone);

            return json_encode($result);
        }
        return view('test_sms', ['type' => $type]);
    }

    /**
     * 首页获取订单(一分钟内的订单数目)
     * @return view
     */
    public function getOrders(Request $request)
    {

        $where = array(
            'admin_id' => Auth::guard("admins")->user()->id,
            'pay_type' => 1,
            'status' => 1,
            'isvalid' => true
        );

        $order_list = DB::table("cater_orders")->where($where)->where('pay_time', '<', time())->where('pay_time', '>=', time() - 60)->get();

        return json_encode($order_list);
    }
}
