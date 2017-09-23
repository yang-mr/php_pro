<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttentions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('attentions')) {
             Schema::create('attentions', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
                $table->unsignedInteger('other_id')->nullable();
                $table->foreign('other_id')->references('id')->on('users');
                $table->softDeletes();  //创建软删除 字段
                $table->smallInteger('status')->default(0); //默认是0 已关注 1 取消了关注
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
        Schema::dropIfExists('attentions');
    }
}
