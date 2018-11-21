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
    return ['status' => 0, 'msg' => $msg];
}

function suc($data_to_merge=null){
    $data = ['status' => 1];
    if ($data_to_merge)
        $data = array_merge($data, $data_to_merge);
    return $data;
}

function user_ins(){
    return new App\Usertable;
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

function comment_ins(){
    return new App\comment;
}

function ad_ins(){
    return new App\ad;
}

Route::get('/', function () {
    return view('welcome');
});

Route::any('apis', function() {
    return ['version' => 0.1];
});

// 登陆注册
Route::any('apis/signup', function() {
    return user_ins()->signup();
});

Route::any('apis/login', function() {
    return user_ins()->login();
});

// 后台登录
Route::any('apis/admin/login', function() {
    return user_ins()->login();
})->middleware('adminlogin');

Route::any('apis/logout', function() {
    return user_ins()->logout();
});

Route::any('apis/is_login', function() {
    return user_ins()->is_login();
});

Route::any('apis/login_Status', function() {
    return user_ins()->login_Status();
});

Route::any('apis/user/read', function() {
    return (new App\Usertable)->read();
});
// 修改密码
Route::any('apis/user/change_password', function() {
    return user_ins()->change_password();
})->middleware('LoginRole');

// 发送短信
Route::any('apis/user/reset_password', function() {
    return user_ins()->reset_password();
});

// 短信修改密码
Route::any('apis/user/validata_captcha', function() {
    return user_ins()->validata_captcha();
});

// 发送邮件
Route::any('apis/user/mail', function() {
    return user_ins()->mail();
});

// 邮件修改密码
Route::any('apis/user/email_valid', function() {
    return user_ins()->email_valid();
});
// // 提问
// Route::any('apis/question/add', function() {
//     return question_ins()->add();
// });

// Route::any('apis/question/change', function() {
//     return question_ins()->change();
// });

// Route::any('apis/question/read', function() {
//     return question_ins()->read();
// });

// Route::any('apis/question/remove', function() {
//     return question_ins()->remove();
// });

// // 回答
// Route::any('apis/answer/add', function() {
//     return answer_ins()->add();
// });

// Route::any('apis/answer/change', function() {
//     return answer_ins()->change();
// });

// Route::any('apis/answer/read', function() {
//     return answer_ins()->read();
// });

// Route::any('apis/answer/vote', function() {
//     return answer_ins()->vote();
// });

// Route::any('apis/timeline', 'CommonController@timeline');



// article
// 中间件，管理员权限
Route::group(['prefix' => 'apis/article', 'middleware' => ['adminRole']], function () {
    Route::any('add', function() {
        return article_ins()->add();
    });
    Route::any('change', function() {
        return article_ins()->change();
    });

    Route::any('remove', function() {
        return article_ins()->remove();
    });

    Route::any('delete', function() {
        return article_ins()->reallyDelete();
    });

    Route::any('restored', function() {
        return article_ins()->restored();
    });
});


Route::any('apis/article/like', function() {
    return article_ins()->like();
});

Route::any('apis/article/read', function() {
    return article_ins()->read();
});

Route::any('apis/article/classify', function() {
    return article_ins()->classify();
});

// 按月份查询
Route::any('apis/article/times', function() {
    return article_ins()->times();
});

// 标签
Route::any('apis/tag/read', function() {
    return tag_ins()->read();
});

// 网站信息
Route::any('apis/webinfo/set', function() {
    return webinfo_ins()->setting();
})->middleware('adminRole');

Route::any('apis/webinfo/read', function() {
    return webinfo_ins()->read();
});

// 友情连接
Route::group(['prefix' => 'apis/link', 'middleware' => ['adminRole']], function () {
    Route::any('add', function() {
        return link_ins()->add();
    });

    Route::any('change', function() {
        return link_ins()->change();
    });

    Route::any('remove', function() {
        return link_ins()->remove();
    });
});
Route::any('apis/link/read', function() {
    return link_ins()->read();
});

// 留言
Route::group(['prefix' => 'apis/message'], function () {
    Route::any('add', function() {
        return message_ins()->add();
    });

    Route::any('reply', function() {
        return message_ins()->reply();
    });

    Route::any('read', function() {
        return message_ins()->read();
    });

    Route::any('change', function() {
        return message_ins()->change();
    });

    Route::any('remove', function() {
        return message_ins()->remove();
    });
});


// 广告
Route::group(['prefix' => 'apis/ad', 'middleware' => ['adminRole']], function () {
    Route::any('add', function() {
        return ad_ins()->add();
    });

    Route::any('change', function() {
        return ad_ins()->change();
    });

    Route::any('remove', function() {
        return ad_ins()->remove();
    });

});
Route::any('apis/ad/read', function() {
    return ad_ins()->read();
});

// 评论文章
Route::group(['prefix' => 'apis/comment'], function () {
    Route::any('add', function() {
        return comment_ins()->add();
    });

    Route::any('read', function() {
        return comment_ins()->read();
    });

    Route::any('remove', function() {
        return comment_ins()->remove();
    });

    Route::any('change', function() {
        return comment_ins()->change();
    });
});

// 上传图片
Route::any('apis/img/upload', 'CommonController@img_upload');
// 删除文件
Route::any('apis/img/delete', 'CommonController@delete_upload');


// 博客banner上传图片
Route::any('apis/img/blogbanner', 'CommonController@blog_banner');
// 博客上传图片
Route::any('apis/img/blogdetail', 'CommonController@fileUpload');