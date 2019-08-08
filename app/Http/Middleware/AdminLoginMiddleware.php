<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class AdminLoginMiddleware
{
    /**
     * 管理员才能登录.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        $user = User::whereName($request->name)->first();

        if ($user && $user->is_admin != 1)
            return response()->json(
                ['status' => 'error', 'code' => 403, 'message' => '你不是管理员，不能登录'], 403
            );

        return $next($request);
    }
}
