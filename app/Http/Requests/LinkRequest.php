<?php

namespace App\Http\Requests;

class LinkRequest extends FormRequest
{
    public function rules()
    {
        if (FormRequest::getPathInfo() == '/api/v2/link/add'){
            return [
                'title' => ['required'],
                'url' => ['required'],
            ];
        } else {
            return [
                'id' => ['required', 'exists:links,id']
            ];
        }

    }

    public function messages()
    {
        return [
            'title.required'=>'标题不能为空',
            'url.required' => '链接不能为空',
            'id.required' => 'id不能为空',
            'id.exists' => 'id不存在',
        ];
    }
}
