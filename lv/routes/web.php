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
    return view('welcome');
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
Route::post('/register', 'User@register');
