<?php

namespace App\Http\Requests;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        if (FormRequest::getPathInfo() == '/api/v2/article/add'){
            return [
                'title' => ['required'],
                'content' => ['required'],
            ];
        } else {
            return [
                'id' => ['required', 'exists:articles,id']
            ];
        }

    }

    public function messages()
    {
        return [
            'title.required'=>'文章标题不能为空',
            'content.required' => '文章内容不能为空',
            'id.required' => '文章id不能为空',
            'id.exists' => '文章id不存在',
        ];
    }
}
