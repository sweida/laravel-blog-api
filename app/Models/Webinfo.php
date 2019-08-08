<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webinfo extends Model
{
    // 接受的字段
    protected $fillable = [
        'title', 'keyword', 'description', 'personinfo', 'github', 
        'icp', 'weixin', 'zhifubao', 'qq', 'phone', 'email', 'startTime'
    ];
}
