<?php

namespace App\Http\Requests;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required', 'max:16', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户ID必须填写',
            'username.max' => '用户ID长度不能超过16个字符',
            'username.min' => '用户ID长度不能小于6个字符'
        ];
    }
}