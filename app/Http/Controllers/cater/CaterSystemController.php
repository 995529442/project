<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\CaterSystem;
use DB;

class CaterSystemController extends Controller
{
    //微餐饮-小程序管理
    public function index(Request $request){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id;

        $system = CaterSystem::where(["admin_id"=>$admin_id,"isvalid"=>true])->first();

        return view('cater.system.index',['system'=>$system]);
    }

    //微餐饮-保存信息
    public function saveSystem(Request $request){
    	$id = (int)$request -> input("id",'');

        if($id){
        	$CaterSystem = CaterSystem::find($id);
        }else{
        	$admins   = Auth::guard('admins')->user();
            $admin_id = $admins->id;

        	$CaterSystem = new CaterSystem;
        	$CaterSystem->admin_id = $admin_id;
            $CaterSystem->isvalid = true;
        }

        $return = array(
          "errcode" => -1,
          "errmsg"  => "失败"
        );
        $CaterSystem->appid = $request -> input("appid",'');
        $CaterSystem->appsecret = $request -> input("appsecret",'');
        $CaterSystem->mch_id = $request -> input("mch_id",'');
        $CaterSystem->apiclient_cert = $request -> input("apiclient_cert",'');
        $CaterSystem->apiclient_key = $request -> input("apiclient_key",'');

        $result = $CaterSystem->save();

        if($result){
        	$return['errcode'] = 1;
        	$return['errmsg']  = "成功"; 
        }

        return json_encode($return);
    }
   
    //微餐饮-上传证书接口
    public function upload(Request $request){
        if ($request->isMethod('post')) {
            $admins   = Auth::guard('admins')->user();
            $admin_id = $admins->id;

            //上传图片具体操作
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES["file"]["tmp_name"];
            $file_error = $_FILES["file"]["error"];
            $file_size = $_FILES["file"]["size"];

            $status = 0;

            if ($file_error > 0) { // 出错
                $message = $file_error;
            } elseif($file_size > 1048576) { // 文件太大了
                $message = "上传文件不能大于1MB";
            }else{
                $date = date('Ymd');
                $file_name_arr = explode('.', $file_name);
                $new_file_name = date('YmdHis') . '.' . $file_name_arr[1];
                $path = "upload/".$admin_id."/cater/system/";
                $file_path = $path . $new_file_name;
                if (file_exists($file_path)) {
                    $message = "此文件已经存在啦";
                } else {
                    //TODO 判断当前的目录是否存在，若不存在就新建一个!
                    if (!is_dir($path)){
                      mkdir($path,0755,true);
                    }
                    $upload_result = move_uploaded_file($file_tmp, $file_path); 
                    //此函数只支持 HTTP POST 上传的文件
                    if ($upload_result) {
                        $status = 1;
                        $message = $file_path;
                    } else {
                        $message = "文件上传失败，请稍后再尝试";
                    }
                }
            }
        } else {
            $message = "参数错误";
        }
        return json_encode(array("status"=>$status,"message"=>$message));
    }
}
