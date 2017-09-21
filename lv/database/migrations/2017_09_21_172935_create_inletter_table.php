<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 发件箱表
 */
class CreateInletterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('inletters')) {
              Schema::create('inletters', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');  //发件人id
                $table->unsignedInteger('letter_id')->nullable(); //信件id
                $table->foreign('letter_id')->references('id')->on('letters');  
                // $table->smallInteger('status')->default(0);  //默认0 未读 1 已读 2 删掉
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
        Schema::dropIfExists('inletters');
    }
}
