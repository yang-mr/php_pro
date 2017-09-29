<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloodtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('bloodtypes')) {
              Schema::create('bloodtypes', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type_id')->comment('血型类型');
                $table->string('type_name', 20);
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
                        Schema::dropIfExists('bloodtypes');
    }
}
