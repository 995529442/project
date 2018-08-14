<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class CaterTemplateController extends Controller
{
    //微餐饮-模板首页
    public function index(Request $request){
        $admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

        $temp_list = DB::table("cater_template")->where(['admin_id'=>$admin_id,'isvalid'=>true])->orderBy('id','desc')->paginate(12);
    	
        return view("cater.template.index",['temp_list'=>$temp_list]);
    }

    /**
     * 微餐饮-新增编辑模板
     * @return view
     */
    public function addTemplate(Request $request)
    {
       $template_id = (int)$request -> input("template_id",0);
       
       $temp_info = "";

       if($template_id){
           $temp_info = DB::table("cater_template")->whereId($template_id)->first();
       }

        return view("cater.template.add_template",['temp_info'=>$temp_info]);
    }

    /**
     * 微餐饮-保存模板
     * @return view
     */
    public function saveTemplate(Request $request)
    {
       $temp_id = (int)$request -> input("temp_id",0);
       $template_id = $request -> input("template_id",'');
       $type = (int)$request -> input("type",0);
       $is_on = (int)$request -> input("is_on",0);

       $data = array(
          'template_id' => $template_id,
          'type' => $type,
          'is_on' => $is_on
       );
       if($temp_id > 0){ //修改
         $result = DB::table("cater_template")->whereId($temp_id)->update($data);
       }else{
          $data['admin_id'] = Auth::guard("admins")->user()->id;
          $data['isvalid'] = true;

          $result = DB::table("cater_template")->insert($data);
       }

        return redirect('cater/template/home');
    } 

    /**
     *  微餐饮-删除模板
     * @return view
     */
    public function delTemplate(Request $request)
    {
       $temp_id = (int)$request -> input("temp_id",0); 

       $return = array(
          "errcode" => -1,
          "errmsg" => "失败"
       );

       if($temp_id > 0){
         $result = DB::table("cater_template")->whereId($temp_id)->update(['isvalid'=>false]);

         if($result){
           $return['errcode'] = 1;
           $return['errmsg'] = '成功';
         }
       }else{
          $return['errmsg'] = '系统错误';
       }

       return json_encode($return);      
    } 
}
