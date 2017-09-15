<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('gifts')) {
                Schema::create('gifts', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type')->nullable();
                $table->string('title')->nullable();
                $table->string('description')->nullable();
                $table->double('price')->nullable();
                $table->float('discount')->nullable()->default(10);
                $table->string('img_url', 255)->nullable();     //显示的图片
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
        Schema::dropIfExists('gifts');
    }
}
