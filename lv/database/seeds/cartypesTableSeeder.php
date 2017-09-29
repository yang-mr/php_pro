<?php

use Illuminate\Database\Seeder;

class cartypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cartypes')->insert([
            'type_id' => 0,
            'type_name' => '--',
        ]);
         DB::table('cartypes')->insert([
            'type_id' => 1,
            'type_name' => '暂未购车',
        ]); DB::table('cartypes')->insert([
            'type_id' => 2,
            'type_name' => '已购车（经济型）',
        ]); DB::table('cartypes')->insert([
            'type_id' => 3,
            'type_name' => '已购车（中档型）',
        ]); DB::table('cartypes')->insert([
            'type_id' => 4,
            'type_name' => '已购车（豪华型）',
        ]); DB::table('cartypes')->insert([
            'type_id' => 5,
            'type_name' => '单位用车',
        ]); DB::table('cartypes')->insert([
            'type_id' => 6,
            'type_name' => '需要时购置',
        ]);
    }
}
