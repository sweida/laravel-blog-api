<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    public function handle($request, Closure $next)
    {
        // 使用CSRF
        //return parent::handle($request, $next);
        // 禁用CSRF
        // return $next($request);

        // 只对GET的方式提交使用CSRF，对POST方式提交表单禁用CSRF
        if($request->method() == 'POST')
        {
            return $next($request);
        }
        
        if ($request->method() == 'GET' || $this->tokensMatch($request))
        {
            return $next($request);
        }
        throw new TokenMismatchException;
    }
    
    protected $except = [
        //
    ];
}
