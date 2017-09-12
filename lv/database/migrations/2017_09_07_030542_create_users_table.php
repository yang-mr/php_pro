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
              // $table->boolean('is_admin')->default(0);
                 $table->string('work', 10);   //0:公务员 1:老师 等
                 $table->string('ischangework', 3); //0 不可能调动工作 1 可能
                 $table->string('education', 2);  //0 小学  1初中 ...
                 $table->dateTime('birthday');
                 $table->smallInteger('salary', 2);  //0: 2000-4000
                 $table->string('hourse_car', 2);  //0 有房无车 1有房有车  2无房有车 3无房无车
                 $table->string('minzu', 10);  //民族
                 $table->smallInteger('weight');  //体重
                 $table->ipAddress('visitor_ip');  //ip地址
                 $table->smallInteger('height'); 
                 $table->string('nation', 10);
                 $table->string('province', 10);
                 $table->string('city', 10);
                 $table->string('area', 10);
                 $table->smallInteger('will_childs'); //想要孩子个数
                 $table->smallInteger('now_status');  //0 单身未婚 1恋爱中 2 离异  3丧偶
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
