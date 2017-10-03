<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableImgs extends Migration
{
    /**
     * @Author   jack_yang
     * @DateTime 2017-09-08T11:16:49+0800
     * type=0 表示用户头像
     * @return   [type]                   [description]
     */
    public function up()
    {
        if (!Schema::hasTable('imgs')) {
            Schema::create('imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->nullable();
            $table->smallInteger('status')->nullable()->default(2); //0审核失败 1审核通过 2审核中
            $table->string('img_url')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();  //用于软删除
            $table->timestamps();
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
        Schema::dropIfExists('imgs');
    }
}
