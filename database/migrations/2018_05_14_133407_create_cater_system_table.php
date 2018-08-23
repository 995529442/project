<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_system', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('appid', 50)->default('')->comment("appid");
            $table->string('appsecret', 50)->default('')->comment("appsecret");
            $table->string('mch_id', 50)->default('')->comment("商户号");
            $table->string('apiclient_cert', 50)->default('')->comment("退款证书");
            $table->string('apiclient_key', 50)->default('')->comment("退款证书");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_system comment '微餐饮-小程序管理表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_system');
    }
}
