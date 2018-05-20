<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->engine = "InnoDb"; 
            $table->increments('id');
            $table->string('module_code',50)->default('')->comment("模块代码");
            $table->string('module_name',50)->default('')->comment("模块名称");
            $table->tinyInteger('is_custom')->defalut('0')->comment("是否定制 1为定制 0为否");
            $table->timestamps();
        });

        DB::statement("alter table module comment '功能模块表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
