<?php

use Illuminate\Database\Seeder;
use App\Model\Nationtype;

class nationtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Nationtype::create([
            'type_id' => '0',
            'type_name' => '--',
            ]);Nationtype::create([
            'type_id' => '1',
            'type_name' => '汉族',
            ]);Nationtype::create([
            'type_id' => '2',
            'type_name' => '藏族',
            ]);Nationtype::create([
            'type_id' => '3',
            'type_name' => '朝鲜族',
            ]);Nationtype::create([
            'type_id' => '4',
            'type_name' => '蒙古族',
            ]);Nationtype::create([
            'type_id' => '5',
            'type_name' => '回族',
            ]);Nationtype::create([
            'type_id' => '6',
            'type_name' => '满族',
            ]);Nationtype::create([
            'type_id' => '7',
            'type_name' => '维吾尔族',
            ]);Nationtype::create([
            'type_id' => '8',
            'type_name' => '壮族',
            ]);Nationtype::create([
            'type_id' => '9',
            'type_name' => '彝族',
            ]);Nationtype::create([
            'type_id' => '10',
            'type_name' => '苗族',
            ]);Nationtype::create([
            'type_id' => '11',
            'type_name' => '其它民族',
            ]);
    }
}
