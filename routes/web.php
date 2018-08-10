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

// 传值
Route::any('/article', function() {
    return view('article', ['name' => 'laravel学习']);
});

// Route::redirect('/', 404);


Route::get('/hello', function() {
    return 'hello laravel';
});

Route::get('/list', function() {
    return 'list';
});