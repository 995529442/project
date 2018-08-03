<?php

namespace App\Http\Controllers\cater;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CaterCategory;
use App\Model\CaterGoods;
use Illuminate\Support\Facades\Auth;
use App\Librarys\uploadFile;
use DB;

class CaterGoodsController extends Controller
{
    //微餐饮-菜品首页
    public function index(Request $request){
        $admins   = Auth::guard('admins')->user();
    	$admin_id = $admins->id;

    	$good_name = $request -> input("good_name","");
        $status = $request -> input("status",0);

    	$CaterGoods = DB::table("cater_goods as good")->where(['good.admin_id'=>$admin_id,'good.isvalid'=>true])
        ->leftJoin("cater_category as cate","cate.id","good.cate_id")
        ->select(['good.id','good.good_name','good.is_hot','good.is_new','good.is_recommend','good.thumb_img','good.original_price','good.now_price','good.introduce','cate.cate_name','good.isout','good.storenum','good.sell_count']);
         
        if($good_name){
        	$CaterGoods->where("good.good_name","like","%$good_name%");
        }
        
        if($status == 1){
            $CaterGoods->where("good.is_hot",1);
        }elseif($status == 2){
            $CaterGoods->where("good.is_new",1);
        }elseif($status == 3){
            $CaterGoods->where("good.is_recommend",1);
        }
        $goods_info = $CaterGoods->orderBy('good.id','desc')->paginate(8);

    	return view("cater.goods.index",[
    		'goods_info' => $goods_info,
    		'good_name'  => $good_name,
            'status'     => $status
    	]);
    }

    //微餐饮-新增/编辑菜品
    public function add_goods(Request $request){
       $admins   = Auth::guard('admins')->user();
       $admin_id = $admins->id;

       $goods_id = (int)$request -> input("goods_id",0);

       $goods_info = CaterGoods::where(['id'=>$goods_id,'isvalid'=>true])->first();

       if(!empty($goods_info)){
            $goods_info['show_thumb_img'] = $goods_info['thumb_img'];
       }
       //获取分类
       $cate_info = CaterCategory::where(["admin_id"=>$admin_id,"isvalid"=>true])->select(['id as cate_id','cate_name'])->get();

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

            $result = uploadFile::uploadImg($admin_id,$_FILES,'/cater/goods/');
        } else {
            $result = ['errcode'=>-1,'errmsg'=>'参数错误'];
        }
        return json_encode($result);
    }

    //微餐饮-保存商品信息
    public function save_goods(Request $request){
        $goods_id = (int)$request -> input("goods_id",0);

        if($goods_id > 0){
            $CaterGoods = CaterGoods::find($goods_id);
        }else{
            $CaterGoods = new CaterGoods;

            $CaterGoods->admin_id = \Auth::guard('admins')->user()->id;

            $CaterGoods->isvalid = true;
        }

        $CaterGoods->good_name = $request -> input("good_name","");
        $CaterGoods->cate_id = (int)$request -> input("cate_id",0);
        $CaterGoods->is_hot = (int)$request -> input("is_hot",0);
        $CaterGoods->is_new = (int)$request -> input("is_new",0);
        $CaterGoods->is_recommend = (int)$request -> input("is_recommend",0);
        $CaterGoods->thumb_img = $request -> input("thumb_img","");
        $CaterGoods->original_price = $request -> input("original_price","");
        $CaterGoods->now_price = $request -> input("now_price","");
        $CaterGoods->introduce = $request -> input("introduce","");
        $CaterGoods->isout = $request -> input("isout",1);
        $CaterGoods->storenum = $request -> input("storenum",0);
        $CaterGoods->virtual_sell_count = $request -> input("virtual_sell_count",0);

        $result = $CaterGoods->save();
        
        if($result){
            return redirect("cater/goods/home");
        }else{
            back();
        }
    }

    //微餐饮-删除菜品
    public function del_goods(Request $request){
        $goods_id = (int)$request -> input("goods_id",0);

        $return = array(
            "errcode" => -1,
            "errmsg"  => "失败"
        );

        if($goods_id > 0){
            $result = DB::table("cater_goods")->whereId($goods_id)->update(array("isvalid"=>false));

            if($result){
                $return['errcode'] = 1;
                $return['errmsg']  = "删除成功";
            }
        }

        return json_encode($return);
    }
}
