<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterOrdersGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_orders_goods', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->integer('order_id')->index()->comment("关联cater_orders表");
            $table->integer('goods_id')->index()->comment("关联cater_goods表");
            $table->string('good_name', 50)->default('')->comment("商品名称");
            $table->decimal('price', 8, 2)->default('0.00')->comment("单价");
            $table->integer('number')->default('0')->comment("数量");
            $table->decimal('total_price', 8, 2)->default('0.00')->comment("总价");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_orders_goods comment '微餐饮-订单商品表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_orders_goods');
    }
}
