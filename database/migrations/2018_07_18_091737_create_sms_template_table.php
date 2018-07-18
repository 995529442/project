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
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->string('template_id',20)->default('')->comment("模板ID");
            $table->tinyInteger('is_on')->defalut('0')->comment("是否启用 0关闭 1启用");
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
