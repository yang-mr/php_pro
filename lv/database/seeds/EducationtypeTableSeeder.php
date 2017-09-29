<?php

use Illuminate\Database\Seeder;
use App\Model\Educationtype;

class EducationtypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Educationtype::create([
            'type_id' => '0',
            'type_name' => '--',
            ]);Educationtype::create([
            'type_id' => '1',
            'type_name' => '小学',
            ]);Educationtype::create([
            'type_id' => '2',
            'type_name' => '初中',
            ]);Educationtype::create([
            'type_id' => '3',
            'type_name' => '高中',
            ]);Educationtype::create([
            'type_id' => '4',
            'type_name' => '专科',
            ]);Educationtype::create([
            'type_id' => '5',
            'type_name' => '本科',
            ]);Educationtype::create([
            'type_id' => '6',
            'type_name' => '硕士',
            ]);Educationtype::create([
            'type_id' => '7',
            'type_name' => '博士',
            ]);
    }
}
