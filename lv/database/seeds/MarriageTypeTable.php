<?php

use Illuminate\Database\Seeder;
use App\Model\Marriagetype;

class MarriageTypeTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Marriagetype::create([
            'type_id' => '0',
            'type_name' => '--',
            ]);
          Marriagetype::create([
            'type_id' => '1',
            'type_name' => '未婚',
            ]);
           Marriagetype::create([
            'type_id' => '2',
            'type_name' => '离异',
            ]);
            Marriagetype::create([
            'type_id' => '3',
            'type_name' => '丧偶',
            ]);
        
    }
}
