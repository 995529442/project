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
    
}
