<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Librarys\MiniappApi;
use DB;

class CaterDeskController extends Controller
{
    //微餐饮-餐桌首页
    public function index(Request $request){    
        $admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

        $desk_list = DB::table("cater_desk")->where(['admin_id'=>$admin_id,'isvalid'=>true])->orderBy('id','desc')->paginate(12);
    	
        return view("cater.desk.index",[
            'desk_list'=>$desk_list
        ]);
    }

    //微餐饮-添加餐桌
    public function addDesk(Request $request){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id;
        
        $desk_id = (int)$request -> input("desk_id",0);

        $desk_info = "";
        if($desk_id){
           $desk_info = DB::table("cater_desk")->whereId($desk_id)->first();          
        }
       
        return view("cater.desk.add_desk",[
            'desk_id' => $desk_id,
            'desk_info'=>$desk_info
        ]);
    }

    //微餐饮-保存餐桌
    public function saveDesk(Request $request){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id;
        
        $return = array(
           "errcode" => -1,
           "errmsg" => "失败"
        );

        $desk_id = (int)$request -> input("desk_id",0);
        $name = $request -> input("name","");

        if($desk_id > 0){
            $result = DB::table("cater_desk")->whereId($desk_id)->update(['name'=>$name]);

            if($result){
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
            }else{
                $return['errmsg'] = "您还没有修改任何数据";
            }
        }else{
            $data = array(
               "admin_id" => $admin_id,
               "name" => $name,
               "isvalid" => true
            );
            $result =  DB::table("cater_desk")->insert($data);

            if($result){
                $return['errcode'] = 1;
                $return['errmsg'] = "成功";                
            }else{
               $return['errmsg'] = "添加失败"; 
            }
        }
        
        return json_encode($return);
    }

    //微餐饮-餐桌处理
    public function operate(Request $request){
        $admins   = Auth::guard('admins')->user();
        $admin_id = $admins->id;
        
        $desk_id = (int)$request -> input("desk_id",0);
        $type = $request -> input("type",'');

        $return = array(
           "errcode" => -1,
           "errmsg" => "失败"
        );

        if($desk_id){
            if($type == 'del'){  //删除
               $result = DB::table("cater_desk")->whereId($desk_id)->update(['isvalid'=>false]);

               if($result){
                 $return['errcode'] = 1;
                 $return['errmsg'] = '删除成功';
               }
            }else{  //生成二维码
                $result = MiniappApi::createQrCode(1,'/cater/desk');

                if($result['errcode'] == 1){ //成功
                   $return['errcode'] = 1;
                   $return['errmsg'] = '生成成功';

                   $path = $result['path'];
                   
                   DB::table("cater_desk")->whereId($desk_id)->update(['img_path'=>$path]);
                }else{
                   $return['errmsg'] = '生成失败';
                }
            }
        }else{
            $return['errmsg'] = '系统错误';
        }

        return json_encode($return);
    }
}
