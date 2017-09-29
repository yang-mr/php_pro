<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $this->call(MarriageTypeTable::class);
        //$this->call(MarriageTypeTable::class);
        //$this->call(VipsTableSeeder::class);
        //$this->call(letterModelTableSeeder::class);
        // $this->call(GiftTableSeeder::class);
        // $this->call(VipsTableSeeder::class);
    }
}
