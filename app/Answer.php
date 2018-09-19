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
            return err('还未登录');

        // 检查参数是否有question_id和内容
        if (!rq('question_id') || !rq('content'))
            return err('question_id and content are required');

        // 检查是否该问题
        $question = question_ins()->find(rq('question_id'));
        if(!$question)
            return err('question not exists');
        
        // 检查是否重复回答
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();
        if ($answered)
            return err('你已经回答过该问题2');

        // 保存数据
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');

        return $this->save() ? 
            suc('回答保存成功3') :
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    // 更新回答
    public function change()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return err('还未登录');

        if(!rq('id') || !rq('content'))
            return err('id and content are required');
        
        $answer = $this->find(rq('id'));
        if ($answer->user_id != session('user_id'))
            return err('你没有权限修改');
        
        $answer->content = rq('content');
        return $answer->save() ?
            suc('修改成功') :
            err('db inster failed');
    }

    // 查看问题quesiton_id的所有回答 或者 查看指定id的回答
    public function read()
    {
        // question_id 或者id 必填
        if (!rq('question_id') && !rq('id'))
            return err('id or question_id are required');

        if (rq('id'))
        {
            // 查找指定id是否存在
            $answer = $this->find(rq('id'));
            if (!$answer)
                return err('answer not exists');
            return suc($answer);
        }

        // 检查问题question_id是否存在
        if (!question_ins()->find(rq('question_id')))
            return err('question not exists');
        
        // 同一问题下的所有回答
        $answer = $this
            ->where('question_id', rq('question_id'))
            ->get();
        
        return suc($answer);
    }
}
