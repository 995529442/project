<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_category', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->string('cate_name',20)->default('')->comment("分类名称");
            $table->unsignedInteger('sort')->default('1')->comment("排序");
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("alter table cater_category comment '微餐饮-分类表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_category');
    }
}
