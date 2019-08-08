<?php

namespace App\Http\Requests;


class TagRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tag' => ['required', 'exists:tags,tag']
        ];

    }

    public function messages()
    {
        return [
            'tag.required'=>'标签名不能为空',
            'tag.exists' => '标签名不存在',
        ];
    }
}
