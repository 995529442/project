<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterCategory;
use App\Model\CaterGoods;
use Illuminate\Support\Facades\Auth;
use DB;

class CaterGoodsController extends Controller
{
    //微餐饮-菜品首页
    public function index(Request $request){
    	$admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

    	$good_name = $request -> input("good_name","");

    	$CaterGoods = CaterGoods::where(['admin_id'=>$admin_id]);
         
        if($good_name){
        	$CaterGoods->where("good_name","like","%$good_name%");
        }

        $goods_info = $CaterGoods->orderBy('id','desc')->paginate(2);

    	return view("cater.goods.index",[
    		'goods_info' => $goods_info,
    		'good_name'  => $good_name
    	]);
    }

    //微餐饮-新增/编辑菜品
    public function add_goods(Request $request){
       $admins   = Auth::guard('admins')->user();
       $admin_id = $admins->id;

       $goods_id = (int)$request -> input("goods_id",0);

       $goods_info = CaterGoods::where(['id'=>$goods_id])->first();

       //获取分类
       $cate_info = CaterCategory::where("admin_id",$admin_id)->select(['id as cate_id','cate_name'])->get();

       return view('cater.goods.add_goods',[
        'goods_info' => $goods_info,
        'cate_info'  => $cate_info
       ]);
    }

    //微餐饮-上传图片接口
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
                $path = "upload/".$admin_id."/cater/goods/";
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
