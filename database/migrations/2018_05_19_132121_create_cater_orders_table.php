<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_orders', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->integer('user_id')->index()->comment("关联cater_users表");
            $table->string('user_name', 20)->default('')->comment("用户名称");
            $table->string('phone', 20)->default('')->comment("联系方式");
            $table->string('batchcode', 20)->default('')->index()->comment("订单号");
            $table->tinyInteger('pay_type')->defalut('0')->comment("是否支付 0未支付 1已支付");
            $table->tinyInteger('status')->defalut('0')->comment("状态 -1已取消 0待付款 1待接单(已支付) 2已接单 3配送中 4配送完成 5已完成 6申请退款 7已退款 8拒绝退款 9已拒单");
            $table->integer('create_time')->default('0')->comment("下单时间");
            $table->integer('pay_time')->default('0')->comment("支付时间");
            $table->integer('shipping_time')->default('0')->comment("配送时间");
            $table->integer('shipping_con_time')->default('0')->comment("配送完成时间");
            $table->integer('confirm_time')->default('0')->comment("完成订单时间");
            $table->integer('recovery_time')->default('0')->comment("未支付订单失效时间");
            $table->tinyInteger('type')->defalut('0')->comment("订单类型 1外卖 2点餐");
            $table->decimal('shipping_fee', 8, 2)->default('0.00')->comment("配送费");
            $table->decimal('package_fee', 8, 2)->default('0.00')->comment("包装费");
            $table->decimal('real_pay', 8, 2)->default('0.00')->comment("真实支付金额(包括配送费、包装费)");
            $table->decimal('total_money', 8, 2)->default('0.00')->comment("总价(不包括配送费、包装费)");
            $table->integer('total_num')->default('0')->comment("总数量");
            $table->string('remark', 255)->default('')->comment("留言");
            $table->string('reject_reason', 255)->default('')->comment("拒绝退款原因");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_orders comment '微餐饮-订单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_orders');
    }
}
