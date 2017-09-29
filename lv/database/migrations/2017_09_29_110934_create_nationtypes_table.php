<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('nationtypes')) {
              Schema::create('nationtypes', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type_id')->comment('民族类型');
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
       Schema::dropIfExists('nationtypes');
    }
}
