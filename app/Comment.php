<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // 添加评论
    public function add()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return err('还未登录');

        if (!rq('content'))
            return err('评论不能为空');
        
        // 检查是否有评论或者回答
        if ( (!rq('question_id') && !rq('answer_id')) || (rq('question_id') && rq('answer_id')) )
            return err('question_id or answer_id is required');

        if (rq('question_id'))
        {
            // 评论问题
            $question = question_ins()->find(rq('question_id'));
            if (!$question)
                return err('问题不存在');

            $this->question_id = rq('question_id');
        } else 
        {
            // 评论答案
            $answer = answer_ins()->find(rq('answer_id'));
            if (!$answer)
                return err('回答不存在');

            $this->answer_id = rq('answer_id');
        }

        // 回复评论
        if (rq('reply_to'))
        {
            $target = $this->find(rq('reply_to'));
            // 检查评论id是否存在
            if (!$target)
                return err('target comment not exists');

            if ($target->user_id == session('user_id'))
                return err('不能回复自己');

            $this->reply_to = rq('reply_to');
        }

        // 保存数据
        $this->content = rq('content');
        $this->user_id = session('user_id');

        return $this->save() ?
            suc() :
            err('db insert failed');
    }
    
    // 查看评论
    public function read()
    {
        if (!rq('question_id') && !rq('answer_id'))
            return err('question_id or answer_id is required');

        if(rq('question_id'))
        {
            $question = question_ins()->find(rq('question_id'));
            if (!$question)
                return err('问题不存在');

            $data = $this->where('question_id', rq('question_id'));
        } else
        {
            $answer = answer_ins()->find(rq('answer_id'));
            if (!$answer)
                return err('回答不存在');
            
            $data = $this->where('answer_id', rq('answer_id'));
        }

        return suc($data->get());
    }

    // 删除评论
    public function remove()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return err('还未登录');
        
        if (!rq('id'))
            return err('id is required');
        
        $comment = $this->find(rq('id'));
        if (!$comment)
            return err('找不到该评论');
        
        if ($comment->user_id != session('user_id'))
            return err('你没有权限删除');
        
        // 先删除此评论下的所有评论
        $this->where('reply_to', rq('id'))->delete();

        // 删除此评论
        return $comment->delete() ?
            suc() :
            err('db delete failed');
    }
}
