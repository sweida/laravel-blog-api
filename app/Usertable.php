<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;
use Mail;
use Symfony\Component\HttpFoundation\Response;

class Usertable extends Model
{
    // 注册api
    public function signup()
    {   
        $username = rq('username');
        $password = rq('password');
        $phone = rq('phone');
        $email = rq('email');
        // // 检查用户名/密码是否为空，
        if (!($username && $password))
            return err('用户名和密码不能为空');

        // 检查用户名是否存在
        $uesr_exists = $this->where('username', $username)->exists();
        if ($uesr_exists)
            return err('用户名已存在');

        // 检查手机号是否存在
        if (rq('phone')) {
            $phone_exists = $this->where('phone', $phone)->exists();
            if ($phone_exists)
                return err('手机号已存在'); 
        }
     
        // 检查邮箱是否存在
        if (rq('email')) {
            $email_exists = $this->where('email', $email)->exists();
            if ($email_exists)
                return err('邮箱已存在'); 
        }

        // 加密密码
        // $hashed_password = Hash::make($password);
        // $hashed_password = bcrypt($password);
        // dd($hashed_password);

        // 存入数据库 , $this指向当前Usertable表
        // $user = $this;
        $this->username = $username;
        $this->password = Hash::make($password);
        $this->phone = $phone;
        $this->email = $email;
        if ($this->save())
            return suc(['id' => $this->id, 'msg' => '注册成功']);
        else
            return err('db insert failed');

        // // dd(Request::get('name'));
        // // dd(Request::has('age'));
        // dd(Request::all());
    }

    public function reads($id){
        $user = $this->find($id);
        return response()->json($user);
    }

    // 获取用户信息
    public function read()
    {
        // 获取单个用户
        if (rq('user_id'))
        {
            $user = $this->find(rq('user_id'));

            // 用户的评论
            if ($user) {
                $comments = comment_ins()->read();
                $messages = message_ins()->read();
                // $messages = message_ins()->where('user_id', rq('user_id'))->get(['content']);
                $user['comments'] = $comments;
                $user['messages'] = $messages;
                return suc(['data' => $user]); 
            } else {
                return err('用户不存在');
            }                
        }    
        // 分页
        $total = $this->count();
        // 每页多少条
        $limit = rq('limit') ?: 10;
        // 页码，从第limit条开始
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        // 按创建时间排序
        $users = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'username', 'is_admin', 'created_at', 'updated_at', 'avatar_url', 'email']);
            // ->keyBy('id');

        // 查看所有提问，默认15条
        return suc(['data' => $users, 'total' => $total]);
    }

    // // 获取所有用户列表
    // public function userlist()
    // {
    //     // 每页多少条
    //     $limit = rq('limit') ?: 10;
    //     // 页码，从第limit条开始
    //     $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

    //     // 按创建时间排序
    //     $list = $this
    //         ->orderBy('created_at')
    //         ->limit($limit)
    //         ->skip($skip)
    //         ->get(['id', 'username', 'is_admin', 'created_at', 'avatar_url', 'email']);
    //         // ->keyBy('id');

    //     // 查看所有提问，默认15条
    //     return ['status' => 0, 'data' => $list];
    // }

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
        session()->forget('is_admin');
        session()->put('username', $user->username);
        session()->put('user_id', $user->id);
        
        // 如果是管理员
        if ($user->is_admin)
            session()->put('is_admin', $user->is_admin);
            
        // return ['ddddd' => $is_admin];
        // 最后一次登录时间
        // $user->last_login = date('Y-m-d h:i:s',time());
        $user->updated_at = time();
        // dd(session()->all());

        if ($user->save() && session('is_admin'))
            return suc(['msg' => '登录成功', 'token' => session('_token'), 'user_id' => $user->id, 'is_admin' => session('is_admin')]);
        else if ($user->save())
            return suc(['msg' => '登录成功', 'token' => session('_token'), 'user_id' => $user->id]);
        else
            return err('服务器有问题，请稍后在登录');
    }

    // 登出
    public function logout() 
    {
        // 全部清空
        // session()->flush();

        // 清除用户名和id
        session()->forget('username');
        session()->forget('user_id');
        session()->forget('is_admin');
        // session()->put('username', null);
        // session()->put('user_id', null);
        // session()->all();
        return suc(['msg' => '退出登陆']);
    }

    // 是否登录
    public function is_login() 
    {
        // dd(session()->all());
        return session('user_id') ? ['登录id'=> session('user_id'), 'username' => session('username')] : false;
    }

    // 是否登录
    public function login_Status() 
    {
        if (session('is_admin')) 
            return suc(['id'=> session('user_id'), 'username' => session('username'), 'is_admin' => session('is_admin')]);
        else if(session('user_id'))
            return suc(['id'=> session('user_id'), 'username' => session('username')]);
        else
            return ['status' => 2, 'msg' => '你还没有登录'];
    }

    // 用旧密码修改密码
    public function change_password()
    {
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
            return err('发送短信太频繁，30分钟后再操作');
        
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
    
    // 发送邮件
    public function mail()
    {
        // 限制多少秒发送一次短信
        if ($this->is_robot(10))
            return err('操作太频繁');

        if (!rq('email'))
            return err('email is required');

        $user = $this->where('email', rq('email'))->first();

        if (!$user)
            return err('该邮箱地址没有注册账号');

        // 时间限制，一段时间后重置次数 300秒
        $longTime = time() - strtotime($user->updated_at);
        if ($longTime > 1800)
            session()->put('captcha_count', 1);
        // 每次发送短信+1
        $captcha_count = session('captcha_count');
        session()->put('captcha_count', $captcha_count+1);
        // 限制5条短信
        if (session('captcha_count') > 6)
            return err('发送邮件太频繁，30分钟后再操作');
        
        // 生成验证码
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;

        if ($user->save()) {
            // 如果验证码保存成功，发送验证码短信
            $this->send_email(rq('email'), $captcha);
            // 保存上一次操作时间
            $this->update_robot_time();
            return suc(['msg' => '邮件验证码已经发送']);
        } else {
            return err('验证码发送失败，请稍后再试');
        }
    }   


    // 发送短信
    public function send_sms()
    {
        return true;
    }

    // 发送邮件验证码
    public function send_email($email, $captcha)
    {
        Mail::raw('验证码是'.$captcha.'，五分钟内有效', function($message) use($email) {
            $message->subject('重置密码');
            $message->to($email);
        });
    }

    // 用手机验证码修改密码
    public function validata_captcha()
    {
        if ($this->is_robot())
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
            return err('验证码已经过期');

        // 验证成功，加密新密码，清空验证码
        $user->password = Hash::make(rq('new_password'));
        $user->phone_captcha = null;
        
        $this->update_robot_time();
        
        return $user->save() ?
            suc(['msg' => '密码修改成功']) :
            err('db update failed');
    }


    // 用邮件验证码修改密码
    public function email_valid()
    {
        if ($this->is_robot())
            return err('操作太频繁');

        if (!rq('email') || !rq('phone_captcha') || !rq('new_password'))
            return err('请输入邮箱地址和验证码和新密码');
        
        // 检查用户是否存在
        $user = $this->where([
            'email'=> rq('email'),
            'phone_captcha'=> rq('phone_captcha')
        ])->first();

        if (!$user)
            return err('验证码错误或者邮箱不对');
        
        // 验证码过期验证
        $longTime = time() - strtotime($user->updated_at);
        if ($longTime > 300)
            return err('验证码已经过期');

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

    // 表格隐藏的字段
    protected $hidden = [
        'password', 'remember_token', 'phone_captcha'
    ];
}
