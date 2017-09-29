<?php

use Illuminate\Database\Seeder;

use App\Model\IncomeType;

class IncomeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         IncomeType::create([
            'type_id' => '0',
            'type_name' => '',
            ]);IncomeType::create([
            'type_id' => '1',
            'type_name' => '2000元以下',
            ]);IncomeType::create([
            'type_id' => '2',
            'type_name' => '2000～5000元',
            ]);IncomeType::create([
            'type_id' => '3',
            'type_name' => '5000～10000元',
            ]);IncomeType::create([
            'type_id' => '4',
            'type_name' => '10000～20000元',
            ]);IncomeType::create([
            'type_id' => '5',
            'type_name' => '20000～50000元',
            ]);IncomeType::create([
            'type_id' => '6',
            'type_name' => '50000元以上',
            ]);
    }
}
