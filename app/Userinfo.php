<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class Userinfo extends Model
{
    // 注册api
    public function signup()
    {   
        $username = Request::get('username');
        $password = Request::get('password');
        // // 检查用户名/密码是否为空，
        if (!($username && $password))
            return ['status' => 0, 'msg' => '用户名和密码不能为空'];

        // 检查用户名是否存在
        $uesr_exists = $this->where('username', $username)->exists();

        if ($uesr_exists)
            return ['status' => 0, 'msg' => '用户名已存在'];

        // 加密密码
        $hashed_password = Hash::make($password);
        // $hashed_password = bcrypt($password);
        // dd($hashed_password);

        // 存入数据库 
        // $this指向Userinfo表
        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        if ($user->save())
            return ['status' => 1, 'id' => $user->id];
        else
            return ['status' => 0, 'msg' => 'db insert failed'];

        // // dd(Request::get('name'));
        // // dd(Request::has('age'));
        // dd(Request::all());
    }

    public function login()
    {
        $username = Request::get('username');
        $password = Request::get('password');
        // // 检查用户名/密码是否为空，
        if (!($username && $password))
            return ['status' => 0, 'msg' => '用户名和密码不能为空'];
        
        // 查找用户是否存在
        $user = $this->where('username', $username)->first();
        if (!$user) 
            return ['status' => 0, 'msg' => '用户名不存在'];
 
        // 检查密码是否正确
        $hashed_password = $user->password;
        if (!Hash::check($password, $hashed_password))
            return ['status' => 0, 'msg' => '密码有误'];
        
        // 写入session
        session()->put('username', $user->username);
        session()->put('id', $user->id);

        return ['status' => 1, 'msg' => '登录成功', 'id' => $user->id];
    }

    // public function has_username_and_password()
    // {
    //     $username = Request::get('username');
    //     $password = Request::get('password');
    //     // // 检查用户名/密码是否为空，
    //     if ($username && $password)
    //         return [$username, $password];
    //     else
    //         false;
    // }
}
