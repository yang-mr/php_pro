<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOneselfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('oneselfs')) {
              Schema::create('oneselfs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description', 1000)->comment('自我介绍');
                $table->smallInteger('status')->nullable()->default(2)->comment('0：审核失败 1：审核通过 2:审核中');
                $table->unsignedInteger('user_id');
                $table->timestamps();
                $table->softDeletes();
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
         Schema::dropIfExists('oneselfs');
    }
}
