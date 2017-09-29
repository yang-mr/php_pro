<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Model\Marriagetype;

class CreateMarriagetypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('marriagetypes')) {
              Schema::create('marriagetypes', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type_id')->comment('婚姻类型');
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
         Schema::dropIfExists('marriagetypes');

    }
}
