<?php

use Illuminate\Database\Seeder;
use App\Model\LetterModel;

class letterModelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    		LetterModel::create([
	        'type' => '0',
	        'content' => '很多男人和父母住在一起，你接受这样的情况吗?',
	        ]);
	        LetterModel::create([
	        'type' => '0',
	        'content' => '你喜欢看电影吗？最近很多新电影上映，想约你一起去看。',
	        ]);
	        LetterModel::create([
	        'type' => '0',
	        'content' => '不懂得花言巧语，只是期待彼此真诚的交流',
	        ]);
	        LetterModel::create([
	        'type' => '0',
	        'content' => '我愿意舍弃明天的早餐给你发一份信，你给我个回复，好吗？',
	        ]);
    }
}
