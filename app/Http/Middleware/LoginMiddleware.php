<?php

namespace App\Http\Middleware;

use Closure;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!(new \App\Usertable)->is_login())
            return response()->json(['status' => false, 'msg' => '请先登录'], 401);
        return $next($request);
    }
}
