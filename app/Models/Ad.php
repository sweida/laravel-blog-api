<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    // 接受的字段
    protected $fillable = [
        'title', 'url', 'type'
    ];

    // 表格隐藏的字段
    protected $hidden = [
        'updated_at'
    ];
}
