<?php

namespace App\Http\Controllers\Api;



use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(UserRequest $request){

        return $this->failed('用户登录成功...');
        
        // 返回单个
        // return $this->success(new UserResource($user));

        // 返回多个
        // return UserResource::collection($users);
    }
}
