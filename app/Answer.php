<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    // 添加回答
    public function add()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return ['status' => 0, 'msg' => '还未登录'];

        // 检查参数是否有question_id和内容
        if (!rq('question_id') || !rq('content'))
            return ['status' => 0, 'msg' => 'question_id and content are required'];

        // 检查是否该问题
        $question = question_ins()->find(rq('question_id'));
        if(!$question)
            return ['stauts' => 0, 'msg' => 'question not exists'];
        
        // 检查是否重复回答
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();
        if ($answered)
            return ['status' => 0, 'msg' => '你已经回答过该问题'];

        // 保存数据
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');

        return $this->save() ? 
            ['status' => 1, 'id' => $this->id, 'msg' => '回答保存成功'] :
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    // 更新回答
    public function change()
    {
        return 1;
    }
}
