<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_template', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('template_id', 20)->default('')->comment("模板ID");
            $table->tinyInteger('type')->defalut('0')->comment("类型，1为验证 2为下单提示");
            $table->tinyInteger('is_on')->defalut('0')->comment("是否启用 0关闭 1启用");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table sms_template comment '短信模板表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_template');
    }
}
