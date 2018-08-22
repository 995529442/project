<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_users', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('weixin_name', 50)->index()->default('')->comment("微信名称");
            $table->string('mobile', 20)->index()->default('')->comment("手机号");
            $table->string('province', 50)->default('')->comment("省份");
            $table->string('city', 50)->default('')->comment("城市");
            $table->string('country', 50)->default('')->comment("县区");
            $table->string('address', 50)->default('')->comment("详细地址");
            $table->string('openid', 255)->default('')->comment("微信openid");
            $table->string('unionid', 255)->default('')->comment("微信unionid");
            $table->string('headimgurl', 255)->default('')->comment("头像");
            $table->unsignedTinyInteger('sex')->default('0')->comment("性别 1为男性，2为女性 0为未知");
            $table->unsignedInteger('order_num')->default('0')->comment("订单总数量");
            $table->unsignedInteger('order_complete_num')->default('0')->comment("完成订单总数量");
            $table->decimal('total_money', 8, 2)->default('0.00')->comment("完成订单总金额");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_users comment '微餐饮-用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_users');
    }
}
