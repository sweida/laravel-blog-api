<?php

namespace App\Http\Middleware;

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
        if(rq('username')){
            $user = user_ins()->where('username', rq('username'))->first();
            if ($user->is_admin != 1)
                return response()->json(err('您不是管理员，不能登录'));
        }
        return $next($request);
    }


}
