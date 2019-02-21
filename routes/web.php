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
    return (new App\Usertable)->signup();
});

Route::any('apis/login', function() {
    return (new App\Usertable)->login();
});

// 后台登录
Route::any('apis/admin/login', function() {
    return (new App\Usertable)->login();
})->middleware('adminlogin');
Route::any('apis/logout', function() {
    return (new App\Usertable)->logout();
});
Route::any('apis/is_login', function() {
    return (new App\Usertable)->is_login();
});
Route::any('apis/login_Status', function() {
    return (new App\Usertable)->login_Status();
});
Route::any('apis/user/read', function() {
    return (new App\Usertable)->read();
});
// 修改密码
Route::any('apis/user/change_password', function() {
    return (new App\Usertable)->change_password();
})->middleware('LoginRole');

// 发送短信
Route::any('apis/user/reset_password', function() {
    return (new App\Usertable)->reset_password();
});

// 短信修改密码
Route::any('apis/user/validata_captcha', function() {
    return (new App\Usertable)->validata_captcha();
});

// 发送邮件
Route::any('apis/user/mail', function() {
    return (new App\Usertable)->mail();
});

// 邮件修改密码
Route::any('apis/user/email_valid', function() {
    return (new App\Usertable)->email_valid();
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
        return (new App\article)->add();
    });
    Route::any('change', function() {
        return (new App\article)->change();
    });
    Route::any('remove', function() {
        return (new App\article)->remove();
    });
    Route::any('delete', function() {
        return (new App\article)->reallyDelete();
    });
    Route::any('restored', function() {
        return (new App\article)->restored();
    });
});


Route::any('apis/article/like', function() {
    return (new App\article)->like();
});
Route::any('apis/article/read', function() {
    return (new App\article)->read();
});
Route::any('apis/article/classify', function() {
    return (new App\article)->classify();
});

// 按月份查询
Route::any('apis/article/times', function() {
    return (new App\article)->times();
});

// 标签
Route::any('apis/tag/read', function() {
    return (new App\tag)->read();
});

// 网站信息
Route::any('apis/webinfo/set', function() {
    return (new App\Webinfo)->setting();
})->middleware('adminRole');

Route::any('apis/webinfo/read', function() {
    return (new App\Webinfo)->read();
});

// 友情连接
Route::group(['prefix' => 'apis/link', 'middleware' => ['adminRole']], function () {
    Route::any('add', function() {
        return (new App\Link)->add();
    });
    Route::any('change', function() {
        return (new App\Link)->change();
    });
    Route::any('remove', function() {
        return (new App\Link)->remove();
    });
});
Route::any('apis/link/read', function() {
    return (new App\Link)->read();
});

// 留言
Route::group(['prefix' => 'apis/message'], function () {
    Route::any('add', function() {
        return (new App\message)->add();
    });
    Route::any('reply', function() {
        return (new App\message)->reply();
    });
    Route::any('read', function() {
        return (new App\message)->read();
    });
    Route::any('change', function() {
        return (new App\message)->change();
    });
    Route::any('remove', function() {
        return (new App\message)->remove();
    });
});


// 广告
Route::group(['prefix' => 'apis/ad', 'middleware' => ['adminRole']], function () {
    Route::any('add', function() {
        return (new App\ad)->add();
    });
    Route::any('change', function() {
        return (new App\ad)->change();
    });
    Route::any('remove', function() {
        return (new App\ad)->remove();
    });

});
Route::any('apis/ad/read', function() {
    return (new App\ad)->read();
});

// 评论文章
Route::group(['prefix' => 'apis/comment'], function () {
    Route::any('add', function() {
        return (new App\comment)->add();
    });
    Route::any('read', function() {
        return (new App\comment)->read();
    });
    Route::any('remove', function() {
        return (new App\comment)->remove();
    });
    Route::any('change', function() {
        return (new App\comment)->change();
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