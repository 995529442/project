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
     *
     * @param Request $request获取推荐 ,热卖,上新菜品
     * @return string
     */
    public function getHotRecGoods(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $type = $request->input("type", '');

        $where = array(
            "admin_id" => $admin_id,
            "isout" => 2,
            "isvalid" => true
        );
        if ($type == "hot") {
            $where['is_hot'] = 1;
        } elseif ($type == "rec") {
            $where['is_recommend'] = 1;
        } elseif ($type == "new") {
            $where['is_new'] = 1;
        }
        //热卖
        $goods = DB::table("cater_goods")
            ->where($where)
            ->select(['id as goods_id', 'good_name', 'thumb_img', 'sell_count', 'virtual_sell_count', 'now_price'])->orderBy("id", "desc")->take(6)->get();
        return json_encode($goods);
    }

    /**
     * 获取点餐菜品列表
     * @param Request $request
     * @return string
     */
    public function getCatGoods(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);

        $data = array();

        $cat_list = DB::table("cater_category")
            ->select(['id as cat_id', 'cate_name'])
            ->where(['admin_id' => $admin_id, 'isvalid' => true])
            ->orderBy("sort", "desc")
            ->orderBy("id", "desc")
            ->get();

        //查询分类下是否与商品
        $new_cat_list = array();
        foreach ($cat_list as $k => $v) {
            $good_list = DB::table("cater_goods")->where(['admin_id' => $admin_id, 'cate_id' => $v->cat_id, 'isout' => 2, 'isvalid' => true])->where("storenum", ">", 0)->get();

            if (count($good_list) > 0) {
                array_push($new_cat_list, $v);
            }
        }

        if ($new_cat_list) {
            foreach ($new_cat_list as $k => $v) {
                //$k = $k+3;
                $data[$k]['title'] = $v->cate_name;
                $data[$k]['id'] = "list" . ($k + 1);

                //获取该分类下的菜品
                $good_list = DB::table("cater_goods")->select(['id as goods_id', 'good_name', 'sell_count', 'virtual_sell_count', 'thumb_img', 'original_price', 'now_price', 'introduce'])->where(['admin_id' => $admin_id, 'cate_id' => $v->cat_id, 'isout' => 2, 'isvalid' => true])->where("storenum", ">", 0)->get();

                $list = array();

                if ($good_list) {
                    foreach ($good_list as $kk => $vv) {
                        $list[$kk]['img'] = "https://" . $_SERVER['HTTP_HOST'] . $vv->thumb_img;
                        $list[$kk]['name'] = $vv->good_name;
                        $list[$kk]['count'] = $vv->sell_count + $vv->virtual_sell_count;
                        $list[$kk]['original_price'] = $vv->original_price;
                        $list[$kk]['price'] = $vv->now_price;
                        $list[$kk]['introduce'] = $vv->introduce;
                        $list[$kk]['id'] = $data[$k]['id'] . "_" . $vv->goods_id;
                    }
                }

                $data[$k]['list'] = $list;

                unset($list);
            }
            return json_encode($data);
        } else {
            return false;
        }

    }

    /**
     * 获取分类菜品
     * @param Request $request
     * @return string
     */
    public function getCateGoods($admin_id, $type)
    {
        $num = 0;
        $where = array(
            "admin_id" => $admin_id,
            "isout" => 2,
            "isvalid" => true
        );
        if ($type == "hot") {
            $num = 1;
            $where['is_hot'] = 1;
        } elseif ($type == "rec") {
            $num = 2;
            $where['is_recommend'] = 1;
        } elseif ($type == "new") {
            $num = 3;
            $where['is_new'] = 1;
        }
        //热卖
        $goods = DB::table("cater_goods")
            ->where($where)
            ->select(['id as goods_id', 'good_name', 'thumb_img', 'sell_count', 'virtual_sell_count', 'now_price', 'original_price'])->orderBy("id", "desc")->get();

        $list = array();
        if ($goods) {
            foreach ($goods as $k => $v) {
                $temp = (object)array();
                $temp->img = "https://" . $_SERVER['HTTP_HOST'] . $v->thumb_img;
                $temp->name = $v->good_name;
                $temp->count = $v->sell_count + $v->virtual_sell_count;
                $temp->original_price = $v->original_price;
                $temp->price = $v->now_price;
                $temp->id = "list" . $num . "_" . $v->goods_id;

                array_push($list, $temp);
            }
        }

        return $list;
    }

    /**
     * 获取结算菜品详情
     * @param Request $request
     * @return string
     */
    public function getSubmitGoods(Request $request)
    {
        $goods_id_arr = $request->input("goods_id_arr", "");

        $goods_id_arr = json_decode($goods_id_arr, true);

        $total_money = 0;
        if ($goods_id_arr) {
            foreach ($goods_id_arr as $k => $v) {
                $goods_info = DB::table("cater_goods")->whereId((int)$v['goods_id'])->first();

                $goods_id_arr[$k]['good_name'] = $goods_info->good_name;

                $money = $goods_info->now_price * $v['number'];

                $goods_id_arr[$k]['money'] = $money;

                $total_money += $money;
            }
        }

        $total_money = round($total_money, 2);

        return json_encode(['goods_id_arr' => $goods_id_arr, 'total_money' => $total_money]);
    }

    /**
     * 获取单个菜品详情
     * @param Request $request
     * @return string
     */
    public function getOneGoods(Request $request)
    {
        $admin_id = (int)$request->input("admin_id", 0);
        $goods_id = (int)$request->input("goods_id", 0);

        $return = array(
            'errcode' => -1,
            'errmsg' => '失败',
            'data' => []
        );

        if ($admin_id) {
            $good_info = DB::table("cater_goods")->whereId($goods_id)->first();

            if ($good_info) {
                //获取菜品展示图
                $figure_img = DB::table("cater_figure_img")->where(['admin_id' => $admin_id, "isvalid" => true, "foreign_id" => $good_info->id, "type" => 2])->get();

                if ($figure_img) {
                    foreach ($figure_img as $k => $v) {
                        $figure_img[$k]->img_path = "https://" . $_SERVER['HTTP_HOST'] . $v->img_path;
                    }
                }
                $good_info->figure_img = $figure_img;

                $return['errcode'] = 1;
                $return['errmsg'] = "成功";
                $return['data'] = $good_info;

            }
        } else {
            $return['errmsg'] = '系统错误';
        }

        return json_encode($return);

    }
}