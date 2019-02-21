<?php

namespace App\Http\Middleware;

use Request;
use Closure;

class RoleMiddleware
{
    /**
     * 只允许管理员登录
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Request::get('username')){
            $user = (new \App\Usertable)->where('username', Request::get('username'))->first();
            if ($user && $user->is_admin != 1)
                return response()->json(['status' =>false, 'msg' => '你不是管理员，不能登录'], 403);
        }
        return $next($request);
    }


}
