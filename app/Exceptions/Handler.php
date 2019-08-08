<?php

namespace App\Exceptions;
use App\ApiCommon\ExceptionReport;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     return parent::render($request, $exception);
    // }

    // jwt
    // public function render($request, Exception $exception)
    // {
    //     // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息
    //     if ($exception instanceof ValidationException) {
    //         return response(['error' => array_first(array_collapse($exception->errors()))], 400);
    //     }
    //     // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
    //     if ($exception instanceof UnauthorizedHttpException) {
    //         return response($exception->getMessage(), 401);
    //     }

    //     return parent::render($request, $exception);
    // }

    public function render($request, Exception $exception)
    {
        if ($request->ajax()){
            // 将方法拦截到自己的ExceptionReport
            $reporter = ExceptionReport::make($exception);
            if ($reporter->shouldReturn()){
                return $reporter->report();
            }
            if(env('APP_DEBUG')){
                //开发环境，则显示详细错误信息
                return parent::render($request, $exception);
            }else{
                //线上环境,未知错误，则显示500
                return $reporter->prodReport();
            }
        }
        return parent::render($request, $exception);
    }


}
