<?php

use Illuminate\Database\Seeder;

class housetypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('housetypes')->insert([
            'type_id' => 0,
            'type_name' => '--',
        ]);
         DB::table('housetypes')->insert([
            'type_id' => 1,
            'type_name' => '暂未购房',
        ]); DB::table('housetypes')->insert([
            'type_id' => 2,
            'type_name' => '需要时购置',
        ]); DB::table('housetypes')->insert([
            'type_id' => 3,
            'type_name' => '已购房（有贷款）',
        ]); DB::table('housetypes')->insert([
            'type_id' => 4,
            'type_name' => '已购房（无贷款）',
        ]); DB::table('housetypes')->insert([
            'type_id' => 5,
            'type_name' => '与人合租',
        ]); DB::table('housetypes')->insert([
            'type_id' => 6,
            'type_name' => '独自租房',
        ]); DB::table('housetypes')->insert([
            'type_id' => 7,
            'type_name' => '与父母同住',
        ]); DB::table('housetypes')->insert([
            'type_id' => 8,
            'type_name' => '住亲朋家',
        ]);
          DB::table('housetypes')->insert([
            'type_id' => 9,
            'type_name' => '住单位房',
        ]); 
    }
}
