<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AdminMiddleware
{
    /**
     * 只允许管理员操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user->is_admin != 1)
            return response()->json(
                ['status' => 'error', 'code' => 403, 'message' => '你没有权限操作'], 403
            );

        return $next($request);
    }


}
