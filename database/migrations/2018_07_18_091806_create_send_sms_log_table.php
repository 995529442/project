<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendSmsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_sms_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->string('mobile',20)->default('')->comment("发送的手机号");
            $table->string('content',255)->default('')->comment("发送内容");
            $table->integer('create_time')->default('0')->comment("创建时间");
            $table->timestamps();
        });

        DB::statement("alter table send_sms_log comment '短信发送日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_sms_log');
    }
}
