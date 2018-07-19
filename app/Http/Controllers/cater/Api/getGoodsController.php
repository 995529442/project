<?php
/**
 * User: 35727
 * Date: 2018/7/19
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class getGoodsController extends Controller
{
    /**
     * 获取推荐,热卖,上新菜品
     * @param Request $request
     * @return string
     */
    public function getHotRecGoods(Request $request) {
      $admin_id = (int)$request -> input("admin_id",0);
      $type = $request -> input("type",'');
      
      $where = array(
         "admin_id" => $admin_id,
         "isout" => 2,
         "isvalid" => true
      );
      if($type == "hot"){
        $where['is_hot'] = 1;
      }elseif($type == "rec"){
        $where['is_recommend'] = 1;
      }elseif($type == "new"){
        $where['is_new'] = 1;
      }
      //热卖
      $goods =  DB::table("cater_goods")
            ->where($where)
            ->select(['id as goods_id','good_name','thumb_img'])->orderBy("id","desc")->take(6)->get();                  
      return json_encode($goods);
    }
}