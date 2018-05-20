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
        $module = $admins->module;  //拥有的模块

        $module_arr = array();
        if(!empty($module)){
           $module_arr = explode(",", $module);
        }

        return view("index",[
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
        return view("main");
    }

    /**
     * 管理员页面
     * @return view
     */
    public function manage(Request $request)
    {
        $username = $request -> input("username","");

        //查找普通管理员列表
        $manage = DB::table('admins')->where(array("type"=>2,"isvalid"=>true));

        if($username){
           $manage->where("username","like","%$username%");
        }

        $manage_info = $manage->orderBy("id","desc")->get();
        
        if($manage_info){
            foreach($manage_info as $k=>$v){
                $admin_module_name = "";
                if(!empty($v->module)){
                    $module_arr = explode(",",$v->module);

                    for($i=0;$i<count($module_arr);$i++){ //查找模块名
                        $module_name = DB::table('module')->where("module_code",$module_arr[$i])->value("module_name");
                        $admin_module_name .= $module_name.",";
                    }
                    unset($module_arr);  
                }
                $manage_info[$k]->admin_module_name = rtrim($admin_module_name,",");
                unset($admin_module_name);             
            }
        }
        return view("manage",['manage_info'=>$manage_info,'username'=>$username]);
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
          "type"     => 2,
          "isvalid"  => true
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
            $result = DB::table('admins')->where(array("id"=>$admin_id,"isvalid"=>true))->update(array("password"=>bcrypt('888888')));

            if($result){
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
        $admin_id = (int)$request -> input("admin_id",0);

        //查找当前用户拥有的模块
        $module = DB::table("admins")->where(['id'=>$admin_id,"isvalid"=>true])->value("module");

        $module_arr = explode(",",$module);

        //查询模块类别
        $module = DB::table("module")->select(['id as module_id','module_code','module_name','is_custom'])->get();

        foreach($module as $k=>$v){
           if(in_array($v->module_code,$module_arr)){
              $module[$k]->is_checked = 1;
           }else{
              $module[$k]->is_checked = 0;
           }
        }

        return view('module',['module'=>$module,'admin_id'=>$admin_id]);
    }

    /**
     * 保存分配模块
     * @return view
     */
    public function saveModule(Request $request)
    {
        $admin_id = (int)$request -> input("admin_id",0);
        $module = $request -> input("module","");

        if($module){
            $module = rtrim($module,",");
        }

        $result = DB::table("admins")->where(['id'=>$admin_id,"isvalid"=>true])->update(['module'=>$module]);

        if($result){
            return json_encode(array("errcode"=>1,"errmsg"=>"成功"));
        }else{
            return json_encode(array("errcode"=>-1,"errmsg"=>"失败"));
        }
    }
    
}
