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

function err($msg=null){
    return ['statu' => 0, 'msg' => $msg];
}

function suc($data_to_merge=null){
    $data = ['status' => 1];
    if ($data_to_merge)
        $data = array_merge($data, $data_to_merge);
    return $data;
}

// function paginate($page=1, $limit=12){
//     $skip = 
// }

function user_ins(){
    return new App\Usertable;
}

function question_ins(){
    return new App\Question;
}

function answer_ins(){
    return new App\Answer;
}

function comment_ins(){
    return new App\Comment;
}

function article_ins(){
    return new App\article;
}

function tag_ins(){
    return new App\tag;
}

function webinfo_ins(){
    return new App\Webinfo;
}

function link_ins(){
    return new App\Link;
}

function message_ins(){
    return new App\message;
}

function ad_ins(){
    return new App\ad;
}

Route::get('/', function () {
    return view('welcome');
});

Route::any('api', function() {
    return ['version' => 0.1];
});

// 登陆注册
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

Route::any('api/userlist', function() {
    return user_ins()->userlist();
});

Route::any('api/user/read', function() {
    return user_ins()->read();
});
// 修改密码
Route::any('api/user/change_password', function() {
    return user_ins()->change_password();
});

// 发送短信
Route::any('api/user/reset_password', function() {
    return user_ins()->reset_password();
});

// 短信修改密码
Route::any('api/user/validata_captcha', function() {
    return user_ins()->validata_captcha();
});

// 提问
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

// 回答
Route::any('api/answer/add', function() {
    return answer_ins()->add();
});

Route::any('api/answer/change', function() {
    return answer_ins()->change();
});

Route::any('api/answer/read', function() {
    return answer_ins()->read();
});

Route::any('api/answer/vote', function() {
    return answer_ins()->vote();
});

// 评论
Route::any('api/comment/add', function() {
    return comment_ins()->add();
});

Route::any('api/comment/read', function() {
    return comment_ins()->read();
});

Route::any('api/comment/remove', function() {
    return comment_ins()->remove();
});

Route::any('api/timeline', 'CommonController@timeline');

// article
Route::any('api/article/add', function() {
    return article_ins()->add();
});

Route::any('api/article/change', function() {
    return article_ins()->change();
});

Route::any('api/article/remove', function() {
    return article_ins()->remove();
});

Route::any('api/article/restored', function() {
    return article_ins()->restored();
});

Route::any('api/article/like', function() {
    return article_ins()->like();
});

Route::any('api/article/read', function() {
    return article_ins()->read();
});

// 标签
Route::any('api/tag/read', function() {
    return tag_ins()->read();
});

// 按月份查询
Route::any('api/article/times', function() {
    return article_ins()->times();
});

// 网站信息
Route::any('api/webinfo/add', function() {
    return webinfo_ins()->add();
});

// 友情连接
Route::any('api/link/add', function() {
    return link_ins()->add();
});

Route::any('api/link/change', function() {
    return link_ins()->change();
});

Route::any('api/link/remove', function() {
    return link_ins()->remove();
});

Route::any('api/link/read', function() {
    return link_ins()->read();
});

// 留言
Route::any('api/message/add', function() {
    return message_ins()->add();
});

Route::any('api/message/read', function() {
    return message_ins()->read();
});

Route::any('api/message/change', function() {
    return message_ins()->change();
});

Route::any('api/message/remove', function() {
    return message_ins()->remove();
});

// 广告
Route::any('api/ad/add', function() {
    return ad_ins()->add();
});

Route::any('api/ad/change', function() {
    return ad_ins()->change();
});

Route::any('api/ad/remove', function() {
    return ad_ins()->remove();
});

Route::any('api/ad/read', function() {
    return ad_ins()->read();
});