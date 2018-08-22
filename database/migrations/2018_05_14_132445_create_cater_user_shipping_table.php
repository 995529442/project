<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterUserShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_user_shipping', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->unsignedInteger('user_id')->comment("关联cater_users的id,用户id");
            $table->string('user_name', 50)->default('')->comment("用户名称");
            $table->string('phone', 20)->default('')->comment("联系方式");
            $table->string('province', 50)->default('')->comment("省份");
            $table->string('city', 50)->default('')->comment("城市");
            $table->string('country', 50)->default('')->comment("县区");
            $table->string('address', 100)->default('')->comment("收货地址");
            $table->string('house_number', 100)->default('')->comment("门牌号");
            $table->tinyInteger('is_default')->default('0')->comment("是否默认地址 1为默认地址,0为否 ");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_user_shipping comment '微餐饮-用户收货地址表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_user_shipping');
    }
}
