<?php

use Illuminate\Database\Seeder;

class bloodtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('bloodtypes')->insert([
            'type_id' => 0,
            'type_name' => '--',
        ]);
         DB::table('bloodtypes')->insert([
            'type_id' => 1,
            'type_name' => 'A型',
        ]); DB::table('bloodtypes')->insert([
            'type_id' => 2,
            'type_name' => 'B型',
        ]); DB::table('bloodtypes')->insert([
            'type_id' => 3,
            'type_name' => 'O型',
        ]); DB::table('bloodtypes')->insert([
            'type_id' => 4,
            'type_name' => 'AB型',
        ]); DB::table('bloodtypes')->insert([
            'type_id' => 5,
            'type_name' => '其它',
        ]); DB::table('bloodtypes')->insert([
            'type_id' => 6,
            'type_name' => '保密',
        ]);
    }
}
