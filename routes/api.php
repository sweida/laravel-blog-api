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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function(){
    Route::get('/user', function( Request $request ){
        return $request->user();
    });

    // 登陆注册
    Route::any('/signup', function() {
        return user_ins()->signup();
    });

    Route::any('/login', function() {
        return user_ins()->login();
    });

    Route::any('/user/read/{id}', function($id) {
        return user_ins()->reads($id);
    });
    /*
     |-------------------------------------------------------------------------------
     | Get All Cafes
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Controller:     API\CafesController@getCafes
     | Method:         GET
     | Description:    Gets all of the cafes in the application
    */
    // Route::get('/cafes', 'API\CafesController@getCafes');

    /*
     |-------------------------------------------------------------------------------
     | Get An Individual Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes/{id}
     | Controller:     API\CafesController@getCafe
     | Method:         GET
     | Description:    Gets an individual cafe
    */
    // Route::get('/cafes/{id}', 'API\CafesController@getCafe');

    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Controller:     API\CafesController@postNewCafe
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
    // Route::post('/cafes', 'API\CafesController@postNewCafe');
});


Route::namespace('Api')->prefix('v1')->group(function () {
    Route::any('/signup','UserController@signup')->name('users.signup');
    Route::any('/login','UserController@login')->name('users.login');
    //当前用户信息
    Route::middleware('api.refresh')->group(function () {
        //用户退出
        Route::any('/logout', 'UserController@logout')->name('users.logout');
        // 当前的用户信息列表
        Route::any('/user/info','UserController@info')->name('users.info');
        //用户信息
        Route::get('/user/{user}','UserController@show')->name('users.show');
        //用户列表
        Route::any('/userlist','UserController@list')->name('users.list');
    });
});