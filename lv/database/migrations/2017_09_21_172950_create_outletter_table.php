<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 收件箱
 */
class CreateOutletterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('outletters')) {
              Schema::create('outletters', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');  //收件人id
                $table->unsignedInteger('letter_id')->nullable(); //信件id
                $table->foreign('letter_id')->references('id')->on('letters');  
                $table->smallInteger('status')->default(0);  //默认0 未读 1 已读 2 删掉
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
         Schema::dropIfExists('outletters');
    }
}
