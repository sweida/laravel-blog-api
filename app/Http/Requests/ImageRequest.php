<?php

namespace App\Http\Requests;

class ImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'image.required'=>'图片不能为空',
            // 'image.exists'=>'图片名已经存在',
        ];
    }
}
