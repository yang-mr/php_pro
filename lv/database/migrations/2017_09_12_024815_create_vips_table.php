<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVipsTable extends Migration
{
    /**
     * Run the migrations.
     *     type: 3天 1月 vip服务等
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vips')) {
            Schema::create('vips', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->double('price')->nullable();
            $table->float('discount')->nullable()->default(10);
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
        Schema::dropIfExists('vips');
    }
}
