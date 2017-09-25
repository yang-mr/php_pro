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
             $table->smallInteger('salary')->nullable()->default(0);
                                        /*  
                                            <option value="0">请选择</option>  
                                            <option label="2000元以下" value="1">2000元以下</option>
                                            <option label="2000～5000元" value="2">2000～5000元</option>
                                            <option label="5000～10000元" value="3">5000～10000元</option>
                                            <option label="10000～20000元" value="4">10000～20000元</option>
                                            <option label="20000～50000元" value="5">20000～50000元</option>
                                            <option label="50000元以上" value="6" selected="selected">50000元以上</option>*/
             $table->smallInteger('hourse')->nullable()->default(0);  
                                            /*<option value="0">--请选择--</option>
                                            <option label="暂未购房" value="1">暂未购房</option>
                                            <option label="需要时购置" value="2">需要时购置</option>
                                            <option label="已购房（有贷款）" value="3">已购房（有贷款）</option>
                                            <option label="已购房（无贷款）" value="4">已购房（无贷款）</option>
                                            <option label="与人合租" value="5">与人合租</option>
                                            <option label="独自租房" value="6">独自租房</option>
                                            <option label="与父母同住" value="7">与父母同住</option>
                                            <option label="住亲朋家" value="8">住亲朋家</option>
                                            <option label="住单位房" value="9">住单位房</option>*/
             $table->smallInteger('car')->nullable()->default(0);  
                                         /* <option value="0">--请选择--</option>
                                            <option label="暂未购车" value="1">暂未购车</option>
                                            <option label="已购车（经济型）" value="2">已购车（经济型）</option>
                                            <option label="已购车（中档型）" value="3">已购车（中档型）</option>
                                            <option label="已购车（豪华型）" value="4">已购车（豪华型）</option>
                                            <option label="单位用车" value="5">单位用车</option>
                                            <option label="需要时购置" value="6">需要时购置</option>*/
             $table->smallInteger('nation', 10)->nullable()->default(0);  
                                           /* <option label="汉族" value="1">汉族</option>
                                            <option label="藏族" value="2">藏族</option>
                                            <option label="朝鲜族" value="3">朝鲜族</option>
                                            <option label="蒙古族" value="4">蒙古族</option>
                                            <option label="回族" value="5">回族</option>
                                            <option label="满族" value="6">满族</option>
                                            <option label="维吾尔族" value="7">维吾尔族</option>
                                            <option label="壮族" value="8">壮族</option>
                                            <option label="彝族" value="9">彝族</option>
                                            <option label="苗族" value="10">苗族</option>
                                            <option label="其它民族" value="11">其它民族</option>*/
             $table->smallInteger('bloodtype')->nullable()->default(0);  
             /*
             血型
              <option value="0">--请选择--</option>
                                            <option label="A型" value="1">A型</option>
                                            <option label="B型" value="2">B型</option>
                                            <option label="O型" value="3">O型</option>
                                            <option label="AB型" value="4">AB型</option>
                                            <option label="其它" value="5">其它</option>
                                            <option label="保密" value="6">保密</option>
              */

             $table->smallInteger('weight')->nullable();  //体重 kg
             $table->ipAddress('visitor_ip')->nullable();  //ip地址
             $table->smallInteger('height')->nullable(); //cm
             $table->string('province', 10)->nullable();
             $table->string('city', 10)->nullable();
             $table->string('area', 10)->nullable();
             $table->smallInteger('children')->nullable()->default(0); //想要孩子个数
             /*
              <option value="0">--请选择--</option>
                                            <option label="无小孩" value="1">无小孩</option>
                                            <option label="有小孩归自己" value="2">有小孩归自己</option>
                                            <option label="有小孩归对方" value="3">有小孩归对方</option>
              */
             $table->smallInteger('now_status')->nullable();  //0 单身未婚 1恋爱中 2 离异  3丧偶

             //备用字段
             $table->string('ry_token', 255)->nullable();
             $table->string('avatar_url', 255)->nullable();
             $table->string('sex', 2)->default('女'); //0:女 1:男
            });
             $table->dateTime('vip_time')->nullable();
             $table->smallInteger('vip')->nullable()->default(0);
             $table->smallInteger('lot_money')->default(0);  //缘分币 1:1 rmb;
             $table->smallInteger('work_location')->default(0);   //工作省份
             $table->smallInteger('work_sublocation')->default(0); //工作城市
             $table->smallInteger('home_location')->default(0);  //家省份
             $table->smallInteger('home_sublocation')->default(0); //家城市

             //新增
             $table->string('true_name', 10)->nullable();  //真是姓名
             $table->string('qq', 11)->nullable();
             $table->string('id_card', 20)->nullable();
             $table->string('avatar_url', 255)->nullable();

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
