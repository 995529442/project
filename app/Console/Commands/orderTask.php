<?php

namespace App\Console\Commands;

date_default_timezone_set('PRC');

use Illuminate\Console\Command;
use DB;

class orderTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderTask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时处理订单任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cancel_order();   //未支付订单过期取消订单
        $this->done_order();     //配送完成订单三日未处理自动已完成
    }

    /**
     * 未支付订单过期取消订单
     */
    public function cancel_order()
    {
        DB::table("cater_orders")->where(['pay_type' => 0, 'status' => 0, 'isvalid' => true])->select(['id as order_id', 'create_time'])->orderBy("id", "desc")->chunk(100, function ($order_list) {
            if (!empty($order_list)) {
                foreach ($order_list as $v) {
                    $create_time = $v->create_time;

                    if ($create_time) {
                        $now_time = date("Y-m-d", time());
                        $days = (strtotime($now_time) - strtotime(date("Y-m-d", $create_time))) / 86400;

                        if ($days > 0) {
                            DB::table("cater_orders")->whereId($v->order_id)->update(['status' => -1]);
                        }
                    }
                }
            }
        });
    }

    /**
     * 未支付订单过期取消订单
     */
    public function done_order()
    {
        DB::table("cater_orders")->where(['pay_type' => 1, 'status' => 4, 'isvalid' => true])->select(['id as order_id', 'create_time'])->orderBy("id", "desc")->chunk(100, function ($order_list) {
            if (!empty($order_list)) {
                foreach ($order_list as $v) {
                    $create_time = $v->create_time;

                    if ($create_time) {
                        $now_time = date("Y-m-d", time());
                        $days = (strtotime($now_time) - strtotime(date("Y-m-d", $create_time))) / 86400;

                        if ($days >= 3) {
                            DB::table("cater_orders")->whereId($v->order_id)->update(['status' => 5]);
                        }
                    }
                }
            }
        });
    }
}
