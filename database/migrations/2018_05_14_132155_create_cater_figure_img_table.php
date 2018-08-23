<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterFigureImgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_figure_img', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->index()->comment("关联admins表");
            $table->string('img_path', 500)->default('')->comment("图片路径");
            $table->unsignedInteger('foreign_id')->comment("关联id,首页展示图关联cate_shop的id,商品展示图关联cater_goods的id");
            $table->unsignedTinyInteger('type')->default('1')->comment("类型 1为店铺展示图 2为商品展示图 3为首页展示图");
            $table->tinyInteger('isvalid')->defalut('0')->comment("是否有效 1为有效 0为无效");
            $table->timestamps();
        });

        DB::statement("alter table cater_figure_img comment '微餐饮-图片展示表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_figure_img');
    }
}
