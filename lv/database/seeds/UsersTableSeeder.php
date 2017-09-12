<?php

use Illuminate\Database\Seeder;
use App\Model\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::create([
	        'name' => 'admin',
	        'email' => '88888@qq.com',
	        'password' => Hash::make('admin')
	        ]);
    }
}
