<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaterGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cater_goods', function (Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->integer('admin_id')->comment("关联admins表");
            $table->unsignedInteger('cate_id')->comment("关联cater_category表id,分类id");
            $table->string('good_name',50)->default('')->comment("名称");
            $table->tinyInteger('is_hot')->default('0')->comment("是否热卖 0为否 1为热卖");
            $table->tinyInteger('is_new')->default('0')->comment("是否新品 0为否 1为新品");
            $table->tinyInteger('is_recommend')->default('0')->comment("是否推荐 0为否 1为推荐");
            $table->string('thumb_img',50)->default('')->comment("缩略图");
            $table->decimal('original_price', 8, 2)->default('0.00')->comment("原价");
            $table->decimal('now_price', 8, 2)->default('0.00')->comment("现价");
            $table->string('introduce',500)->default('')->comment("介绍");
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("alter table cater_goods comment '微餐饮-商品表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cater_goods');
    }
}
