<?php

namespace App\Http\Requests;


class CommentRequest extends FormRequest
{
    public function rules()
    {
        switch (FormRequest::getPathInfo()){
            case '/api/v2/comment/add':
                return [
                    'content' => ['required'],
                    'article_id' => ['required', 'exists:articles,id,deleted_at,NULL'], // 忽略软删除的数据
                    'reply_id' => ['exists:comments,id'],
                ];
            case '/api/v2/comment/edit':
                return [
                    'id' => ['required', 'exists:comments,id'],
                    'content' => ['required'],
                ];
            case '/api/v2/comment/read':
                return [
                    'article_id' => ['required', 'exists:articles,id,deleted_at,NULL'],
                ];
            case '/api/v2/comment/delete':
                return [
                    'id' => ['required', 'exists:comments,id']
                ];
        }
    }

    public function messages()
    {
        return [
            'content.required' => '评论内容不能为空',
            'reply_id.exists' => '回复id不存在',
            'article_id.required' => '文章id不能为空',
            'article_id.exists' => '文章id不存在',
            'id.required' => 'id不能为空',
            'id.exists' => 'id不存在',
        ];
    }
}
