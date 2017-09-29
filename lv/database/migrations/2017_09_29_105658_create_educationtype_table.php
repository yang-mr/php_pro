<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('educationtypes')) {
              Schema::create('educationtypes', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type_id')->comment('学历类型');
                $table->string('type_name', 10);
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
                 Schema::dropIfExists('educationtypes');
    }
}
