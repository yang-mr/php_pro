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

Route::get('user/user_desc/{id?}', 'UserController@user_desc')->name('user_desc');

//发送邮件
Route::get('home/send_email/{id}', 'UserController@send_email');

Route::get('home/attention/{id}', 'UserController@attention')->name('add_attention');

Route::get('home/cancel_attention/{id}', 'UserController@cancel_attention')->name('cancel_attention');

// Route::get('/{id}', function ($id) {
//     return $id;
// })->middleware('checkid:10');

// Route::get('/', function () {
// 	$url = route('test');
// 	return $action = Route::currentRouteName();
// })->name('test');

/*
	注册用户 修改资料等
 */
Route::post('/show', 'UserController@show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/commit_msg', 'UserController@editUserMsg');

Route::post('home/upload_avatar', 'UserController@editAvatar');

Route::get('/home/base_mean', 'HomeController@baseMeans')->name('base_mean');

Route::post('/home/edit_msg', 'HomeController@editMsg')->name('edit_msg');

Route::post('/home/edit_oneself', 'HomeController@editOneself')->name('edit_oneself');

Route::get('/home/edit_img', 'HomeController@editImg')->name('edit_img');

Route::get('/home/oneself', 'HomeController@oneself')->name('oneself');


/**
 * 首页业务
 */
Route::post('/home/user_search', 'HomeController@userSearch')->name('user_search');

/**
 * 上传图片
 */
Route::post('/home/upload_Img', 'HomeController@uploadImg')->name('upload_img');

/**
 * 管理员中心
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

Route::get('/gift/attentions', 'GiftController@getGiftsByType')->name('gift_attention');


/**
 * 写信 业务
 */
Route::get('/letter/index/{id}', 'LetterController@index')->name('write_letter')->middleware('vip');

Route::post('/letter/insertLetter', 'LetterController@insertLetter')->name('insert_letter')->middleware('vip');

Route::get('/letter/in_box', 'LetterController@in_box')->name('in_letter');

Route::get('/letter/out_box', 'LetterController@out_box')->name('out_letter');

Route::get('/letter/look_letter/{letter_id}', 'LetterController@look_letter')->name('look_letter');

//设置信件状态
Route::get('/letter/set_status/{letter_id}', 'LetterController@set_status')->name('set_status');

/**
 * 错误提示
 */
Route::get('/error/no_vip', function() {
    return view('tip_view.no_vip');
})->name('no_vip')->middleware('auth');

