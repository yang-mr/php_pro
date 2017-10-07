<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 接管路由
$api = app('Dingo\Api\Routing\Router');
 
// 配置api版本和路由
$api->version('v1', function ($api) {
    // 授权组
    $api->group(['namespace' => 'Api'], function ($api) {
        $api->post('login', 'ApiController@login');
    });
});