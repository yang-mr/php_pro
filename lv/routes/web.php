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

Route::get('/', 'HomeController@home');

Route::get('home/user_desc/{id}', 'UserController@user_desc');

//发送邮件
Route::get('home/send_email/{id}', 'UserController@send_email');


Route::get('home/attention/{id}', 'UserController@attention');

Route::get('home/cancel_attention/{id}', 'UserController@cancel_attention');

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

Route::get('home/edit_msg', function () {
	return view('edit_msg');
});

Route::post('/commit_msg', 'UserController@editUserMsg');

Route::post('home/upload_avatar', 'UserController@editAvatar');

/**
 * 管理员中心
 */
Route::get('/admin/login', function () {
	return view('admin.login');
})->name('admin_login');

Route::post('admin/checkLogin', 'Admin\AdminController@checkLogin')->name('admin_login_check');

Route::get('admin/adminCenter', 'Admin\AdminController@adminCenter')->name('admin_center');

/**
 * 得到所有的服务订单资源 
 */
Route::get('admin/adminVips', 'Admin\AdminController@adminVips')->name('admin_vip');


