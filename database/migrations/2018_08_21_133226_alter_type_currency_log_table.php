<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeCurrencyLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cater_currency_log', function (Blueprint $table) {
            $table->tinyInteger('type')->defalut('0')->comment("类型 1为增加 2为减少");
            $table->decimal('currency_money', 8, 2)->default('0.00')->comment("金额");
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
