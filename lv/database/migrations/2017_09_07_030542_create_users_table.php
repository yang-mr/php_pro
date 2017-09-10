<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // var_dump(Schema::hasTable('users'));
        // exit;
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('remember_token', 100)->nullable()->default('null');
            $table->timestamps();
            });
        } else {
            //增加字段
            Schema::table('users', function (Blueprint $table) {
               // $table->string('phone');
               // $table->string('description');
               // $table->string('requist');
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
        Schema::dropIfExists('users');
    }
}
