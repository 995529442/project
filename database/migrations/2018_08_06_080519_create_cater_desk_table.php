<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterDeskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_desk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->string('name',20)->default('')->comment("桌号");
            $table->string('img_path',50)->default('')->comment("二维码路径");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_desk');
    }
}
