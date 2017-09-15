<?php

use Illuminate\Database\Seeder;
use App\Model\Gift;

class GiftTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gift::create([
	        'type' => '0',
	        'price' => 39,
	        'title' => 'vip 半月卡',
	        'description' => '我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊',
	        ]);
          	Gift::create([
	        'type' => '1',
	        'price' => 59,
	        'discount' => 9,
	        'title' => 'vip 月卡',
	        'description' => '我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊',
	        ]);
	        Gift::create([
	        'type' => '2',
			'price' => 99,
			'discount' => 8.8,
	        'title' => 'vip 季卡',
	        'description' => '我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊',
	        ]);
	        Gift::create([
	        'type' => '3',
	        'price' => 199,
	        'title' => 'vip 半年卡',
	        'description' => '我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊',
	        ]);
	        Gift::create([
	        'type' => '4',
	        'price' => 299,
	        'title' => 'vip 年卡',
	        'description' => '我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊我的vip月卡啊',
	        ]);
    }
}
