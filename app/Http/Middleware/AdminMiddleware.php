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
        if (!user_ins()->is_login())
            return response()->json(err('您还没有登录'));
        
        // 检查是否管理员
        $user = user_ins()->find(session('user_id'));
        if ($user['is_admin'] != 1)
            return response()->json(err('您不是管理员，没有权限'));

        return $next($request);
    }
}

