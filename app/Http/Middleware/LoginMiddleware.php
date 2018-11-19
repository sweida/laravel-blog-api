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
        if (!user_ins()->is_login())
            return response()->json(['status' => 2, 'msg' => '你还没有登录']);
            // return response()->json(Response::HTTP_UNAUTHORIZED);      // 401
        return $next($request);
    }
}
