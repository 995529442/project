<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_log', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('send_to', 20)->default('')->comment("发送给的对象");
            $table->string('content', 500)->default('')->comment("发送内容");
            $table->tinyInteger('is_success')->defalut('0')->comment("是否成功，0失败，1成功");
            $table->integer('send_time')->default('0')->comment("发送时间");
            $table->string('remark', 500)->default('')->comment("备注");
            $table->timestamps();
        });

        DB::statement("alter table send_log comment '发送日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_log');
    }
}
