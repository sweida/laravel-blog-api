<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Http\Response;
class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     $response = $next($request);
    //     $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
    //     $allow_origin = [
    //         // 'http://localhost:8000/api',
    //         // 'http://localhost:8888/api',
    //     ];
    //     if (in_array($origin, $allow_origin)) {
    //         $response->header('Access-Control-Allow-Origin', $origin);
    //         $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
    //         $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
    //         $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
    //         $response->header('Access-Control-Allow-Credentials', 'true');
    //     }
    //     return $response;
    // }    

    public function handle($request, Closure $next) {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        // $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }


}
