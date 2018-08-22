<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\CaterSystem;
use App\Librarys\uploadFile;
use DB;

class CaterSystemController extends Controller
{
    //微餐饮-小程序管理
    public function index(Request $request)
    {
        $admins = Auth::guard('admins')->user();
        $admin_id = $admins->id;

        $system = CaterSystem::where(["admin_id" => $admin_id, "isvalid" => true])->first();

        return view('cater.system.index', ['system' => $system]);
    }

    //微餐饮-保存信息
    public function saveSystem(Request $request)
    {
        $id = (int)$request->input("id", '');

        if ($id) {
            $CaterSystem = CaterSystem::find($id);
        } else {
            $admins = Auth::guard('admins')->user();
            $admin_id = $admins->id;

            $CaterSystem = new CaterSystem;
            $CaterSystem->admin_id = $admin_id;
            $CaterSystem->isvalid = true;
        }

        $return = array(
            "errcode" => -1,
            "errmsg" => "失败"
        );
        $CaterSystem->appid = $request->input("appid", '');
        $CaterSystem->appsecret = $request->input("appsecret", '');
        $CaterSystem->mch_id = $request->input("mch_id", '');
        $CaterSystem->apiclient_cert = $request->input("apiclient_cert", '');
        $CaterSystem->apiclient_key = $request->input("apiclient_key", '');

        $result = $CaterSystem->save();

        if ($result) {
            $return['errcode'] = 1;
            $return['errmsg'] = "成功";
        }

        return json_encode($return);
    }

    //微餐饮-上传证书接口
    public function upload(Request $request)
    {
        if ($request->isMethod('post')) {
            $admins = Auth::guard('admins')->user();
            $admin_id = $admins->id;

            $result = uploadFile::uploadImg($admin_id, $_FILES, '/cater/system/');
        } else {
            $result = ['errcode' => -1, 'errmsg' => '参数错误'];
        }
        return json_encode($result);
    }
}
