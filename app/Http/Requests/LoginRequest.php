<?php

namespace App\Http\Requests;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            // 'id' => ['exists:shop_user,id'],
            'username' => ['required', 'exists:users,username'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            // 'id.exists'=>'用户不存在',
            'username.required'=>'用户名不能为空',
            'username.exists'=>'用户名不存在',
            'password.required' => '密码不能为空',
        ];
    }
}