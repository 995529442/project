<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsOpenCurrencyCaterShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cater_shop', function (Blueprint $table) {
            $table->tinyInteger('is_open_currency')->defalut('0')->comment("是否开启购物币支付 0为关闭 1为开启");
            $table->tinyInteger('is_open_sms')->defalut('0')->comment("是否开启短信通知 0为关闭 1为开启");
            $table->tinyInteger('is_open_mail')->defalut('0')->comment("是否开启邮件通知 0为关闭 1为开启");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
