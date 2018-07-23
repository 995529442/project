<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Librarys\uploadFile;
use DB;

class CaterHomeController extends Controller
{
    //微餐饮-首页
    public function index(Request $request){
        $admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

    	$home_info = DB::table("cater_figure_img")->select(['id','img_path'])->where(['admin_id'=>$admin_id,'isvalid'=>true,'type'=>3])->get();

        return view("cater.home.index",['home_info'=>$home_info]);
    }

    //微餐饮-上传图片接口
    public function upload(Request $request){
        if ($request->isMethod('post')) {
            $admins   = Auth::guard('admins')->user();
            $admin_id = $admins->id;

            $result = uploadFile::uploadImg($admin_id,$_FILES,'/cater/home/');
        } else {
            $result = ['errcode'=>-1,'errmsg'=>'参数错误'];
        }
        return json_encode($result);
    }

    //微餐饮-删除首页展示图片
    public function delFigureImg(Request $request){
       $img_id = (int)$request -> input('img_id',0);

       $result = DB::table("cater_figure_img")->whereId($img_id)->update(['isvalid'=>false]);

       if($result){
          return json_encode(['errcode'=>1,'errmsg'=>'成功']);
       }else{
          return json_encode(['errcode'=>-1,'errmsg'=>'失败']);
       }
    }

    //微餐饮-保存
    public function save(Request $request){
        $figure_img_id = $request -> input('figure_img_id','');
        $figure_img = $request -> input('figure_img','');

        $shop_id = DB::table("cater_shop")->where(['admin_id'=>Auth::guard('admins')->user()->id,'isvalid'=>true])->value("id");
        //商家展示图
        if($figure_img){
            for($k=0;$k<count($figure_img_id);$k++){
                $insert_data = array(
                   "admin_id" => Auth::guard('admins')->user()->id,
                   "img_path" =>$figure_img[$k],
                   "foreign_id" => $shop_id?$shop_id:0,
                   "type" => 3,
                   "isvalid" => true
                );
                
                if((int)$figure_img_id[$k] > 0){  //修改
                  DB::table("cater_figure_img")->whereId((int)$figure_img_id[$k])->update(['img_path'=>$figure_img[$k],]);
                }else{
                  DB::table("cater_figure_img")->insert($insert_data);
                }
            }
        }

        return redirect('cater/home/index');
    }
}
