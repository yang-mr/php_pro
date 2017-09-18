<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGiftsTable extends Migration
{
    /**
     * Run the migrations.
     * 用户收藏礼物 用户收到的礼物 我赠送的礼物
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('userGifts')) {
            Schema::create('userGifts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type')->nullable();    //0: 用户收藏礼物 1：用户收到的礼物  2：我赠送的礼物
            $table->unsignedInteger('gift_id')->nullable();
            $table->foreign('gift_id')->references('id')->on('gifts');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('otheruser_id')->nullable();
            $table->foreign('otheruser_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();  //用于软删除
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userGifts');
    }
}
