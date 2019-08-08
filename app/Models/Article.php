<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    // 软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // 接受的字段
    protected $fillable = [
        'title', 'content', 'like', 'clicks', 'img', 'classify', 'desc'
    ];

    // 表格隐藏的字段
    protected $hidden = [
        'updated_at'
    ];
}
