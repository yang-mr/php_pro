<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('letters')) {
              Schema::create('letters', function (Blueprint $table) {
                $table->increments('id');
                $table->string('content');
                $table->unsignedInteger('from_id')->nullable();
                $table->foreign('from_id')->references('id')->on('users');  //发件人id
                $table->unsignedInteger('to_id')->nullable(); //收件人id
                $table->foreign('to_id')->references('id')->on('users');  
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
        Schema::dropIfExists('letters');
    }
}
