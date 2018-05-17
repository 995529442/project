<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view("index",[
            'username' => $username,
            'type' => $type
        ]);
    }
    
    /**
     * 后台右边主页
     * @return view
     */
    public function main()
    {
        return view("main");
    }

    /**
     * 管理员页面
     * @return view
     */
    public function manage()
    {
        //查找普通管理员列表
        $manage_info = DB::table('admins')->where("type",2)->orderBy("id","desc")->get();
        return view("manage",['manage_info'=>$manage_info]);
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
        $username = $request -> input("username","");

        $data = array(
          "username" => $username,
          "password" => bcrypt('888888'),
          "type"     => 2
        );
        $result = DB::table('admins')->insert($data);

        if($result){
            return json_encode(array("errcode"=>1,"errmsg"=>"新增成功"));
        }else{
            return json_encode(array("errcode"=>-1,"errmsg"=>"新增失败"));
        }
    }
    
     /**
     * 重置密码
     * @return view
     */
    public function reset_admin(Request $request)
    {
        $admin_id = (int)$request -> input("admin_id","");

        $return = array("errcode"=>-1,"errmsg"=>"重置失败");

        if($admin_id > 0){
            $result = DB::table('admins')->where("id",$admin_id)->update(array("password"=>bcrypt('888888')));

            if($result){
                $return['errcode'] = 1;
                $return['errmsg'] = "重置成功";
            }
        }

        return json_encode($return);
    }

}
