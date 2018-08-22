<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterCurrencyLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_currency_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('operate_from', 50)->default('')->comment("操作人");
            $table->integer('user_id')->comment("操作对象id");
            $table->string('operate_to', 50)->default('')->comment("操作对象");
            $table->string('remark', 255)->default('')->comment("操作内容");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_currency_log comment '微餐饮-购物币日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_currency_log');
    }
}
