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


// 版本号
Route::get('/version', function() {
    return ["name" => "laravel-blog-api", "author" => "sweida", "version" => "v2"];
});

Route::namespace('Api')->prefix('v2')->group(function () {
    Route::post('/signup','UserController@signup')->name('users.signup');
    Route::post('/login','UserController@login')->name('users.login');
    // 管理员登录
    Route::middleware('adminLogin')->group(function () {
        Route::post('/admin/login', 'UserController@login')->name('users.adminlogin');
    });
    //当前用户信息
    Route::middleware('api.refresh')->group(function () {
        Route::post('/logout', 'UserController@logout')->name('users.logout');
        Route::get('/user/info','UserController@info')->name('users.info');
        Route::post('/user','UserController@show')->name('users.show');
        Route::post('/user/resetpassword','UserController@resetpassword')->name('users.resetpassword');
    });
    Route::post('/user/send_email','CommonController@send_email')->name('users.send_email');
    Route::post('/user/check_captcha','CommonController@check_captcha')->name('users.check_captcha');
    
    Route::middleware(['api.refresh', 'adminRole'])->group(function () {
        Route::post('/user/list','UserController@list')->name('users.list');
    });

    // 图片上传又拍云
    Route::middleware(['api.refresh', 'adminRole'])->group(function () {
        Route::post('/image/upload', 'ImageController@upload')->name('image.upload');
        Route::post('/image/delete', 'ImageController@delete')->name('image.delete');
    });

    // 添加文章模块
    Route::post('/article/list', 'ArticleController@list')->name('article.list');
    Route::post('/tag/list', 'TagController@orderbytag')->name('tag.list');
    Route::get('/article/classify', 'ArticleController@classify')->name('article.classify');
    Route::post('/article/like', 'ArticleController@like')->name('article.like');
    Route::post('/article','ArticleController@detail')->name('article.detail');
    Route::middleware(['api.refresh', 'adminRole'])->group(function () {
        Route::post('/article/add', 'ArticleController@add')->name('article.add');
        Route::post('/article/edit', 'ArticleController@edit')->name('article.edit');
        Route::post('/article/delete','ArticleController@delete')->name('article.delete');
        Route::post('/article/restored','ArticleController@restored')->name('article.restored');
        Route::post('/article/reallydelete','ArticleController@reallyDelete')->name('article.reallyDelete');
    });

    // 评论模块
    Route::post('/comment/add', 'CommentController@add')->name('comment.add');
    Route::post('/comment/list', 'CommentController@list')->name('comment.list');
    Route::post('/comment/read','CommentController@read')->name('comment.read');
    Route::middleware('api.refresh')->group(function () {
        Route::post('/comment/edit', 'CommentController@edit')->name('comment.edit');
        Route::post('/comment/delete','CommentController@delete')->name('comment.delete');
        // 获取个人的所有评论
        Route::get('/comment/person','CommentController@person')->name('comment.person');
    });
    Route::middleware('adminLogin')->group(function () {
        Route::post('/comment/deletes','CommentController@deletes')->name('comment.deletes');
    });
    // 留言模块
    Route::post('/message/add', 'MessageController@add')->name('message.add');
    Route::post('/message/list', 'MessageController@list')->name('message.list');
    Route::middleware('api.refresh')->group(function () {
        Route::post('/message/edit', 'MessageController@edit')->name('message.edit');
        Route::post('/message/delete','MessageController@delete')->name('message.delete');
        // 个人留言
        Route::get('/message/person','messageController@person')->name('message.person');
    });
    Route::middleware('adminLogin')->group(function () {
        Route::post('/message/deletes','MessageController@deletes')->name('message.deletes');
    });

    // 友情链接模块
    Route::post('/link/list', 'LinkController@list')->name('link.list');
    Route::middleware(['api.refresh', 'adminRole'])->group(function () {
        Route::post('/link/add', 'LinkController@add')->name('link.add');
        Route::post('/link/edit', 'LinkController@edit')->name('link.edit');
        Route::post('/link/delete','LinkController@delete')->name('link.delete');
    });

    // 图片广告模块
    Route::post('/ad/list', 'AdController@list')->name('ad.list');
    Route::post('/ad', 'AdController@show')->name('ad.show');
    Route::middleware(['api.refresh', 'adminRole'])->group(function () {
        Route::post('/ad/add', 'AdController@add')->name('ad.add');
        Route::post('/ad/edit', 'AdController@edit')->name('ad.edit');
        Route::post('/ad/delete','AdController@delete')->name('ad.delete');
        Route::post('/webinfo/set', 'WebinfoController@set')->name('webinfo.set');
    });

    // 网站信息模块
    Route::get('/webinfo/read', 'WebinfoController@read')->name('webinfo.read');

});

