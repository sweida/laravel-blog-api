<?php

namespace App\Http\Requests;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户ID必须填写',
            'password.required' => '密码不能为空',
        ];
    }
}