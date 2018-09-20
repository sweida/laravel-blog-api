<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class Usertable extends Model
{
    // 注册api
    public function signup()
    {   
        $username = rq('username');
        $password = rq('password');
        $phone = rq('phone');
        // // 检查用户名/密码是否为空，
        if (!($username && $password))
            return err('用户名和密码不能为空');

        // 检查用户名是否存在
        $uesr_exists = $this->where('username', $username)->exists();

        if ($uesr_exists)
            return err('用户名已存在');

        // 加密密码
        $hashed_password = Hash::make($password);
        // $hashed_password = bcrypt($password);
        // dd($hashed_password);

        // 存入数据库 
        // $this指向Userinfo表
        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        $user->phone = $phone;
        if ($user->save())
            return suc(['id' => $user->id]);
        else
            return err('db insert failed');

        // // dd(Request::get('name'));
        // // dd(Request::has('age'));
        // dd(Request::all());
    }

    // 登录api
    public function login()
    {
        $username = Request::get('username');
        $password = Request::get('password');
        // // 检查用户名/密码是否为空，
        if (!($username && $password))
            return err('用户名和密码不能为空');
        
        // 查找用户是否存在
        $user = $this->where('username', $username)->first();
        if (!$user) 
            return err('用户名不存在');
 
        // 检查密码是否正确
        $hashed_password = $user->password;
        if (!Hash::check($password, $hashed_password))
            return err('密码有误');
        
        // 写入session
        session()->put('username', $user->username);
        session()->put('user_id', $user->id);

        return suc(['msg' => '登录成功', 'user_id' => $user->id]);
    }

    // 登出
    public function logout() 
    {
        // 全部清空
        // session()->flush();

        // 清除用户名和id
        session()->forget('username');
        session()->forget('user_id');
        // session()->put('username', null);
        // session()->put('user_id', null);
        // session()->all();
        return suc(['msg' => '退出登陆']);
    }

    // 是否登录
    public function is_login() 
    {
        // dd(session()->all());
        return session('user_id') ?: false;
    }

    // 用旧密码修改密码
    public function change_password()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return err('还未登录');

        if (!rq('old_password') || !rq('new_password'))
            return err('新密码和旧密码不能为空');

        $user = $this->find(session('user_id'));

        if (!Hash::check(rq('old_password'), $user->password))
            return err('旧密码错误');
        
        $user->password = Hash::make(rq('new_password'));
        return $user->save() ? 
            suc(['msg' => '密码修改成功']) :
            err('db update failed');
    }

    // 找回密码,发送短信验证码
    public function reset_password()
    {
        // $current_time = time();

        if (!rq('phone'))
            return err('phone is required');

        $exists = $this->where('phone', rq('phone'))->exists();

        if (!$exists)
            return err('找不到该手机号码');
        
        // 生成验证码
        $captcha = $this->generate_captcha();
        
        $user->phone_captcha = $captcha;
        
        if ($user->save()) {
            // 如果验证码保存成功，发送验证码短信
            $this->send_sms();
            return suc(['msg' => '短信已经发送']);
        } else {
            return err('验证码保存失败');
        }
    }   
    
    // 生成验证码
    public function generate_captcha()
    {
        return rand(1000, 9999);
    }

    // 发送短信
    public function send_sms()
    {
        return true;
    }

    // 验证验证码修改密码
    public function validata_captcha()
    {
        if (!rq('phone') || !rq('phone_captcha') || !rq('new_password'))
            return err('phone and new_password and phone_captcha are required');
        
        $user = $this->where([
            'phone'=> rq('phone'),
            'phone_captcha'=> rq('phone_captcha')
        ])->first();

        if (!'$user')
            return err('验证码错误或者手机号不对');

        $user->password = Hash::make(rq('new_password'));
        return $user->save() ?
            suc(['msg' => '密码修改成功']) :
            err('db update failed');
    }

    public function answers()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }    

}
