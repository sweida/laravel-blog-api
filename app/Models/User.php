<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{


    // 接受的字段
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'avatar_url'
    ];

    // 表格隐藏的字段
    protected $hidden = [
        'password', 'remember_token', 'is_admin'
    ];

    //将密码进行加密
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}