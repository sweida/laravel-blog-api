<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{


    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

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