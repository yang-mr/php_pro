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
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable()->default('null');
            $table->timestamps();
            $table->softDeletes();  //用于软删除
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->string('requist')->nullable();
            $table->boolean('is_admin')->default(0);
             $table->string('work', 10)->nullable();   //0:企业、事业单位 1:专业技术 2.商业、服务业人员 3.水利业生产人员 4.生产、运输设备操作
             $table->string('ischangework', 3)->nullable(); //0 不可能调动工作 1 可能
             $table->string('education', 2)->nullable();  //0 小学  1初中 2高中 3专科 4本科 5硕士 6博士
             $table->date('birthday')->nullable();
             $table->smallInteger('salary')->nullable();  //0: 2000-4000  1: 4000-10000 2: 10000-20000 3:20000-40000 4:40000以上
             $table->string('hourse_car', 2)->nullable();  //0 有房无车 1有房有车  2无房有车 3无房无车
             $table->string('minzu', 10)->nullable();  //民族 
             $table->smallInteger('weight')->nullable();  //体重 kg
             $table->ipAddress('visitor_ip')->nullable();  //ip地址
             $table->smallInteger('height')->nullable(); //cm
             $table->string('nation', 10)->nullable();
             $table->string('province', 10)->nullable();
             $table->string('city', 10)->nullable();
             $table->string('area', 10)->nullable();
             $table->smallInteger('will_childs')->nullable(); //想要孩子个数
             $table->smallInteger('now_status')->nullable();  //0 单身未婚 1恋爱中 2 离异  3丧偶

             //备用字段
             $table->string('ry_token', 255)->nullable();
             $table->string('avatar_url', 255)->nullable();
             $table->string('sex', 2)->default('女'); //0:女 1:男
            });
             $table->dateTime('vip')->nullable();
        } else {
            //增加字段
            Schema::table('users', function (Blueprint $table) {
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
