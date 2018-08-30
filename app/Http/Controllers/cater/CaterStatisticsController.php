<?php
/**
 * Created by PhpStorm.
 * User: 12183
 * Date: 2018/8/29
 * Time: 11:46
 */

namespace App\Http\Controllers\cater;

date_default_timezone_set('PRC');

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class CaterStatisticsController extends Controller
{
	//微餐饮-统计首页
	public function index(Request $request)
	{
		$admins = Auth::guard('admins')->user();
		$admin_id = $admins->id;
		//当前日期
		$categories = "";  //日期
		$tangshi = ""; //堂食
		$waimai = ""; //外卖

		for($k=6;$k>=0;$k--){
			$micro_time = strtotime("-$k day");
			$day = date("m-d",$micro_time);

			$categories .= $day.",";
			//获取点餐和外卖的订单情况
			$where = array(
				"admin_id"=>$admin_id,
				"pay_type"=>1,
				"status" =>5,
				"isvalid"=>true
			);

			$tangshi_count =  DB::table("cater_orders")->where($where)->where("create_time",">",strtotime(date("Y-m-d",$micro_time)))
				->where("create_time","<=",strtotime(date("Y-m-d",$micro_time))+3600*24-1)->where(['type'=>1])->count();
			$waimai_count = DB::table("cater_orders")->where($where)->where("create_time",">",strtotime(date("Y-m-d",$micro_time)))
				->where("create_time","<=",strtotime(date("Y-m-d",$micro_time))+3600*24-1)->where(['type'=>2])->count();

			$tangshi .= $tangshi_count.",";
			$waimai .= $waimai_count.",";
		}

        //获取微信和支付宝支付份额
		$wx_count = DB::table("cater_orders")->where($where)->where(['payment_type'=>0])->count();
		$currency_count = DB::table("cater_orders")->where($where)->where(['payment_type'=>1])->count();

		$total_count = $wx_count+$currency_count;

		return view('cater.statistics.index',[
			"categories" => rtrim($categories,','),
			"tangshi" => rtrim($tangshi,','),
			"waimai" => rtrim($waimai,','),
			'wx_count' => empty($total_count)?0:round($wx_count/$total_count,2),
			'currency_count' => empty($total_count)?0:round($currency_count/$total_count,2)
		]);
	}
}
