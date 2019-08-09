<?php

namespace App\Http\Requests;


class MessageRequest extends FormRequest
{

    public function rules()
    {

        switch (FormRequest::getPathInfo()){
            case '/api/v2/message/add':
                return [
                    'content' => ['required'],
                    'reply_id' => ['exists:messages,id'],
                ];
            case '/api/v2/message/edit':
                return [
                    'id' => ['required', 'exists:messages,id'],
                    'content' => ['required'],
                ];
            case '/api/v2/message/delete':
                return [
                    'id' => ['required', 'exists:messages,id']
                ];
        }

    }

    public function messages()
    {
        return [
            'content.required' => '留言内容不能为空',
            'reply_id.exists' => '回复id不存在',
            'id.required' => 'id不能为空',
            'id.exists' => 'id不存在',
        ];
    }   
    
}
