<?php

namespace App\Http\Requests;


class MessageReplyRequest extends FormRequest
{
    public function rules()
    {

        switch (FormRequest::getPathInfo()){
            case '/api/v2/message/reply':
                return [
                    'message_id' => ['required', 'exists:messages,id'],
                    'content' => ['required']
                ];
            case '/api/v2/message/reply/delete':
                return [
                    'id' => ['required', 'exists:message_replies,id']
                ];
        }

    }

    public function messages()
    {
        return [
            'content.required' => '留言内容不能为空',
            'message_id.exists' => '回复id不存在',
            'id.required' => 'id不能为空',
            'id.exists' => 'id不存在',
        ];
    }   
}
