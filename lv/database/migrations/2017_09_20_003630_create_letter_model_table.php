<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('lettermodels')) {
              Schema::create('lettermodels', function (Blueprint $table) {
                $table->increments('id');
                $table->string('content');
                $table->smallInteger('type')->nullable();  //0：女士模块 1:男士模块
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
        Schema::dropIfExists('userGifts');
    }
}
