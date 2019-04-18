<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;


class FormRequest extends BaseFormRequest
{
    public function authorize()
    {
        //false代表权限验证不通过，返回403错误
        //true代表权限认证通过
        return true;
    }

    // 取消默认的重定向跳转，返回错误信息，
    protected function failedValidation(Validator $validator) {
        $error= $validator->errors()->all();
        throw new HttpResponseException(
            response()->json(['status'=>'error','code'=>10000,'message'=>$error[0]])
        );
    }

}
