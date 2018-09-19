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
function rq($key=null, $default=null){
    if(!$key) return Request::all();
    return Request::get($key, $default);
}

function user_ins(){
    return new App\Usertable;
}

function question_ins(){
    return new App\Question;
}

Route::get('/', function () {
    return view('welcome');
});

Route::any('api', function() {
    return ['version' => 0.1];
});

Route::any('api/signup', function() {
    return user_ins()->signup();
});

Route::any('api/login', function() {
    return user_ins()->login();
});

Route::any('api/logout', function() {
    return user_ins()->logout();
});

Route::any('api/is_login', function() {
    return user_ins()->is_login();
});

Route::any('api/question/add', function() {
    return question_ins()->add();
});

Route::any('api/question/change', function() {
    return question_ins()->change();
});

Route::any('api/question/read', function() {
    return question_ins()->read();
});

Route::any('api/question/remove', function() {
    return question_ins()->remove();
});