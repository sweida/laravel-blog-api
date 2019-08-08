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
    //用户注册
    public function signup(UserRequest $request){
        User::create($request->all());
        return $this->setStatusCode(201)->success('用户注册成功');
    }

    //用户登录
    public function login(LoginRequest $request){
        $token=Auth::guard('api')->attempt(
            ['username'=>$request->username,'password'=>$request->password]
        );
        if($token){
            return $this->success(['token' => 'bearer ' . $token]);
        }
        return $this->failed('密码有误！');
    }
    
    //用户退出
    public function logout(){
        Auth::guard('api')->logout();
        return $this->success('退出成功...');
    }

    //返回当前登录用户信息
    public function info(){
        $user = Auth::guard('api')->user();
        return $this->success($user);
    }

    //返回指定用户信息
    public function show(User $user){
        // return $this->success(new UserResource($user));
        return $this->success($user);
    }

    //返回用户列表 3个用户为一页
    public function list(){
        $users = User::paginate(3);
        return $this->success($users);
    }

}
