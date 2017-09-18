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
/**
 * 首页
 */
Route::get('/', 'IndexController@index')->name('index')->middleware('auth');

// Route::get('/', 'HomeController@home');

Route::get('user/user_desc/{id}', 'UserController@user_desc')->name('user_desc');

//发送邮件
Route::get('home/send_email/{id}', 'UserController@send_email');

Route::get('home/attention/{id}', 'UserController@attention')->name('add_attention');

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
 *
 * 
 */
Route::get('/admin/login', function () {
	return view('admin.login');
})->name('admin_login');

Route::get('/admin/logout', 'Admin\AdminController@logout')->name('admin_logout');

Route::post('admin/checkLogin', 'Admin\AdminController@checkLogin')->name('admin_login_check');

Route::get('admin/adminCenter', 'Admin\AdminController@adminCenter')->name('admin_center')->middleware('checkAdmin');

Route::get('admin/adminVips', 'Admin\AdminController@adminVips')->name('admin_vip')->middleware('checkAdmin');

Route::get('admin/adminGifts', 'Admin\AdminController@adminGifts')->name('admin_gift')->middleware('checkAdmin');

Route::post('admin/add_gift', 'Admin\AdminController@addGift')->name('admin_add_gift');

Route::post('admin/edit_gift', 'Admin\AdminController@editGift')->name('admin_edit_gift');

/**
 * vip 业务
 */
Route::get('/vip/index', 'VipController@index')->name('vip_index');

/**
 * gift 业务
 */
Route::get('/gift/index', 'GiftController@index')->name('gift_index');

Route::get('/gift/type/{type}', 'GiftController@getGiftsFromType')->name('gift_type');

Route::get('/gift/collect/{gift_id}', 'GiftController@collectGift')->name('gift_collect');

Route::get('/gift/other_type/{type}', 'GiftController@getGiftsByType')->name('other_type');

Route::get('/gift/attentions/{type}', 'GiftController@getGiftsByType')->name('other_type');

