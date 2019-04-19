<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Hash;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class UserController extends Controller
{
    public function index(UserRequest $request){

        return $this->failed('用户登录成功...');
        
        // 返回单个
        // return $this->success(new UserResource($user));

        // 返回多个
        // return UserResource::collection($users);
    }


    //用户注册
    public function signup(UserRequest $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $phone = $request->get('phone');
        $email = $request->get('email');

        $uesr_exists = User::whereUsername($username)->exists();
        if ($uesr_exists)
            return $this->failed('用户名已经存在');

        $email_exists = User::whereEmail($email)->exists();
        if ($email_exists)
            return $this->failed('邮箱已经存在');

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return $this->message('注册成功');
    }


    // 登录api
    public function login(LoginRequest $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        
        // 查找用户是否存在
        $user = User::where('username', $username)->first();
        if (!$user) 
            return $this->failed('用户名不存在');
 
        // 检查密码是否正确
        if (!Hash::check($password, $user->password))
            return $this->failed('密码有误');

        $params = ['username'=>$username,'password'=>$password];

        $token = Auth::guard('api')->attempt($params);
        if($token) {
            return $this->success(['token' => 'bearer ' . $token]);
        }

        return $this->failed('登录失败',400);


        // $user->updated_at = time();
        // $user->save();
        // return $this->message('登录成功');


        // // 写入session
        // session()->forget('is_admin');
        // session()->put('username', $user->username);
        // session()->put('user_id', $user->id);
        
        // // 如果是管理员
        // if ($user->is_admin)
        //     session()->put('is_admin', $user->is_admin);
            
        // // return ['ddddd' => $is_admin];
        // // 最后一次登录时间
        // // $user->last_login = date('Y-m-d h:i:s',time());
        // $user->updated_at = time();
        // // dd(session()->all());

        // if ($user->save() && session('is_admin'))
        //     return suc(['msg' => '登录成功', 'token' => session('_token'), 'user_id' => $user->id, 'is_admin' => session('is_admin')]);
        // else if ($user->save())
        //     return suc(['msg' => '登录成功', 'token' => session('_token'), 'user_id' => $user->id]);
        // else
        //     return err('服务器有问题，请稍后在登录');
    }
    
    //用户退出
    public function logout(){
        Auth::guard('api')->logout();
        return $this->success('退出成功...');
    }

    //返回当前登录用户信息
    public function info(){
        $user = Auth::guard('api')->user();
        return $this->success('个人中心');
    }


}
