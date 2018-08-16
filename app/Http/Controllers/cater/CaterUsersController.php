<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\CaterUsers;
use DB;

class CaterUsersController extends Controller
{
    //微餐饮-用户首页
    public function index(Request $request){
       $admins   = Auth::guard('admins')->user();
       $admin_id = $admins->id;

       $weixin_name = $request -> input('weixin_name','');
       $mobile      = $request -> input('mobile','');
       $sex         = (int)$request -> input('sex',0);

       //获取用户信息
       $CaterUsers = CaterUsers::where(["admin_id"=>$admin_id,'isvalid'=>true]);

       if($weixin_name){
       	  $CaterUsers->where("weixin_name",'like',"%$weixin_name%");
       }

       if($mobile){
       	  $CaterUsers->where("mobile",'like',"%$mobile%");
       }

       if($sex){
       	  $CaterUsers->where("sex",'=',$sex);
       }
       $user_info = $CaterUsers->orderBy('id','desc')->paginate(12);

       return view('cater.users.index',[
          'user_info' => $user_info,
          'weixin_name' => $weixin_name,
          'mobile' => $mobile,
          'sex' => $sex
       ]);
    }

    //微餐饮-购物币充值
    public function add_currency(Request $request){
       $user_id = (int)$request -> input('user_id',0);
       
       return view('cater.users.add_currency',[
          'user_id' => $user_id
       ]);
    }
 
     //微餐饮-保存购物币
    public function save_currency(Request $request){
        $user_id    = (int)$request -> input('user_id',0);
        $money      = (float)$request -> input('money',0);

        $return = array(
           "errcode" => -1,
           "errmsg" => "失败"
        );

        if($user_id >0){
             try {
                DB::beginTransaction();
               
                DB::table("cater_users")->whereId($user_id)->increment("currency_money",$money);

                DB::table("cater_currency_log")->insert([
                   "admin_id" => Auth::guard('admins')->user()->id,
                   "operate_from" =>  Auth::guard('admins')->user()->username,
                   "user_id" => $user_id,
                   "operate_to" => DB::table("cater_users")->whereId($user_id)->value("weixin_name"),
                   "remark" => "后台充值".$money."元",
                   "isvalid" => true
                ]);

                DB::commit();

                $return['errcode'] = 1;
                $return['errmsg'] = "成功";

                return json_encode($return);

              }catch (\Exception $exception) {
                DB::rollback();//事务回滚
                throw $exception;
              }           
        }else{
           $return['errmsg'] = "用户不存在";
        }

        return json_encode($return);
    }  

    //微餐饮-购物币日志
    public function currency_log(Request $request){
       $admins   = Auth::guard('admins')->user();
       $admin_id = $admins->id;

       $user_id    = (int)$request -> input('user_id',0);      

       $log_list = DB::table("cater_currency_log")->where(['admin_id'=>$admin_id,'user_id'=>$user_id,'isvalid'=>true])->orderByDesc("id")->paginate(12);

       return view('cater.users.currency_log',[
          'log_list' => $log_list
       ]);
    } 
}
