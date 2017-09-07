<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	session(['name.last' => 'yw']);
	session(['name.first' => 'rose']);

	var_dump(session('name'));
   // return view('welcome');
});
// Route::get('/{id}', function ($id) {
//     return $id;
// })->middleware('checkid:10');

// Route::get('/', function () {
// 	$url = route('test');
// 	return $action = Route::currentRouteName();
// })->name('test');

/*
	注册用户
 */
Route::post('/show', 'UserController@show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/edit_msg', function () {
	return view('edit_msg');
});

Route::post('/commit_msg', 'UserController@editUserMsg');
