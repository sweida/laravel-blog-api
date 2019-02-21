<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * 只有管理员才能操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 检查是否登录
        if (!(new \App\Usertable)->is_login())
            return response()->json(['status' => false, 'msg' => '请先登录'], 401);
        
        // 检查是否管理员
        $user = (new \App\Usertable)->find(session('user_id'));
        if ($user['is_admin'] != 1)
            return response()->json(['status' => false, 'msg' => '你不是管理员，没有权限'], 403);

        return $next($request);
    }
}

