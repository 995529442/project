<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->increments('id');
            $table->string('username',20)->unique()->comment("用户名");
            $table->string('password',60)->comment("密码");
            $table->tinyInteger('type')->comment("类型：1为超级管理员 2为普通管理员");
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("alter table admins comment '后台管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
