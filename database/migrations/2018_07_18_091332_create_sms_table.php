<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('accountsid', 50)->default('')->comment("accountsid");
            $table->string('token', 50)->default('')->comment("token");
            $table->string('appid', 50)->default('')->comment("appid");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table sms comment '云之讯短信设置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
}
