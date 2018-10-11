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

        // 检查手机号是否存在
        $phone_exists = $this->where('phone', $phone)->exists();
        if ($phone_exists)
            return err('手机号已存在');      

        // 加密密码
        // $hashed_password = Hash::make($password);
        // $hashed_password = bcrypt($password);
        // dd($hashed_password);

        // 存入数据库 , $this指向当前Usertable表
        // $user = $this;
        $this->username = $username;
        $this->password = Hash::make($password);
        $this->phone = $phone;
        if ($this->save())
            return suc(['id' => $this->id, 'msg' => '注册成功']);
        else
            return err('db insert failed');

        // // dd(Request::get('name'));
        // // dd(Request::has('age'));
        // dd(Request::all());
    }

    // 获取用户信息
    public function read()
    {
        if (!rq('id'))
            return err('required user id');
        
        // 查询指定的字段
        $get = ['id', 'username', 'intro'];
        $user = $this->find(rq('id'), $get);
        $data = $user->toArray();

        // $question_count = question_ins()->where('user_id', rq('id'))->count();
        // $answer_count = answer_ins()->where('user_id', rq('id'))->count();
        $questions = question_ins()->where('user_id', rq('id'))->get();
        $answers = answer_ins()->where('user_id', rq('id'))->get();

        $data['answers'] = $answers;
        $data['questions'] = $questions;

        return suc(['data' => $data]);
    }

    // 获取所有用户列表
    public function userlist()
    {
        // 每页多少条
        $limit = rq('limit') ?: 10;
        // 页码，从第limit条开始
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        // 按创建时间排序
        $list = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'username', 'avatar_url', 'email'])
            ->keyBy('id');

        // 查看所有提问，默认15条
        return ['status' => 0, 'data' => $list];
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
        
        // 最后一次登录时间
        // $user->last_login = date('Y-m-d h:i:s',time());
        $user->updated_at = time();

        return $user->save() ?
            suc(['msg' => '登录成功', 'user_id' => $user->id]) : 
            err('服务器有问题，请稍后在登录');
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
        return session('user_id') ? ['登录id'=> session('user_id')] : false;
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

    // 生成验证码
    public function generate_captcha()
    {
        return rand(1000, 9999);
    }

    // 发送短信验证码
    public function reset_password()
    {
        // 限制多少秒发送一次短信
        if ($this->is_robot(10))
            return err('操作太频繁');

        if (!rq('phone'))
            return err('phone is required');

        $user = $this->where('phone', rq('phone'))->first();

        if (!$user)
            return err('找不到该手机号码');

        // 时间限制，一段时间后重置次数
        $longTime = time() - strtotime($user->updated_at);
        if ($longTime > 100)
            session()->put('captcha_count', 1);
        // 每次发送短信+1
        $captcha_count = session('captcha_count');
        session()->put('captcha_count', $captcha_count+1);
        // 限制5条短信
        if (session('captcha_count') > 6)
            return err('发送短信太频繁，5分钟后再操作');
        
        // 生成验证码
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;

        if ($user->save()) {
            // 如果验证码保存成功，发送验证码短信
            $this->send_sms();
            // 保存上一次操作时间
            $this->update_robot_time();
            return suc(['msg' => '短信已经发送']);
        } else {
            return err('验证码保存失败');
        }
    }   
    
    // 发送短信
    public function send_sms()
    {
        return true;
    }

    // 用验证验修改密码
    public function validata_captcha()
    {
        if ($this->is_robot(2))
            return err('操作太频繁');

        if (!rq('phone') || !rq('phone_captcha') || !rq('new_password'))
            return err('phone and new_password and phone_captcha are required');
        
        // 检查用户是否存在
        $user = $this->where([
            'phone'=> rq('phone'),
            'phone_captcha'=> rq('phone_captcha')
        ])->first();

        if (!$user)
            return err('验证码错误或者手机号不对');
        
        // 短信过期验证
        $longTime = time() - strtotime($user->updated_at);
        if ($longTime > 180)
            return err('短信已经过期');

        // 验证成功，加密新密码，清空验证码
        $user->password = Hash::make(rq('new_password'));
        $user->phone_captcha = null;
        
        $this->update_robot_time();
        
        return $user->save() ?
            suc(['msg' => '密码修改成功']) :
            err('db update failed');
    }

    // 检查是否机器人
    public function is_robot($time = 10)
    {
        // 如果没有last_action_time说明接口没被调用过
        if (!session('last_action_time'))
            return false;

        $elapsed = time() - session('last_action_time');
        return !($elapsed > $time);
    }

    // 上一次操作时间
    public function update_robot_time()
    {
        session()->put('last_action_time', time());
    }

    public function answers()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }    

}
