<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        $userAuth = Auth::guard('api')->user();
        $user = User::find($userAuth->user_id);

        if ($user->is_admin != 1)
            return response()->json(
                ['status' => 'error', 'code' => 403, 'message' => '你没有权限操作'], 403
            );

        return $next($request);
    }


}
