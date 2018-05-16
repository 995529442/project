<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_shop', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->string('name',20)->default('')->comment("名称");
            $table->time('begin_time')->comment('营业开始时间');
            $table->time('end_time')->comment('营业结束时间');
            $table->tinyInteger('status')->default('1')->comment("状态：1为营业中 2为打烊");
            $table->string('logo',50)->default('')->comment("餐厅LOGO");
            $table->string('introduce',500)->nullable()->comment("餐厅介绍");
            $table->integer('province_id')->default('0')->comment("关联address表");
            $table->integer('city_id')->default('0')->comment("关联address表");
            $table->integer('area_id')->default('0')->comment("关联address表");
            $table->string('address',255)->default('')->comment("详细地址");
            $table->string('phone',20)->default('')->comment("联系方式");
            $table->string('longitude',50)->default('')->comment("经度");
            $table->string('latitude',50)->default('')->comment("纬度");           

            $table->timestamps();
        });

        DB::statement("alter table cater_shop comment '微餐饮-餐厅管理表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_shop');
    }
}
