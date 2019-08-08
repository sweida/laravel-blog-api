<?php

use Illuminate\Http\Request;
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

Route::get('testRedis','RedisController@testRedis')->name('testRedis');
Route::get('redis/set','RedisController@set')->name('redis.set');
Route::get('redis/get','RedisController@get')->name('redis.get');