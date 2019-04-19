<?php

namespace App\Http\Requests;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required', 'max:16', 'min:4'],
            'email' => ['required'],
            'password' => ['required', 'max:32', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户ID必须填写',
            'username.max' => '用户ID长度不能超过16个字符',
            'username.min' => '用户ID长度不能小于4个字符',
            'email.required' => '邮箱不能为空',
            'password.required' => '密码不能为空',
            'password.max' => '密码长度不能超过32个字符',
            'password.min' => '密码长度不能少于6个字符', 
        ];
    }
}