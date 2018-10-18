<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    //添加评论
    public function add() {
        if (!rq('article_id') || !rq('content'))
            return err('article_id and content are required');

        $article = article_ins()->find(rq('article_id'));
        if (!$article)
            return err('文章不存在');

        // 登录的拿到用户id，没有登录的可以填用户名
        if (session('user_id'))
            $this->user_id = session('user_id');
        else
            $this->username = rq('username');

        $this->content = rq('content');
        $this->article_id = rq('article_id');

        return $this->save() ? 
            suc(['mgs' => '评论成功']) :
            err('db insert failed');
    }

    public function remove() {
        if (!rq('id'))
            return err('id is required');

        $comment = $this->find(rq('id'));
        if (!$comment) 
            return err('id不存在');

        // 如果不是管理员
        $user = user_ins()->find(session('user_id'));
        if ($user->is_admin != 1) {
            if (!$comment->user_id)
                return err('匿名评论不能删除！');

            if ($comment->user_id != session('user_id'))
                return err('你没有权限删除！');
        }

        return $comment->delete() ?
            suc(['msg' => '删除成功！']) :
            err('db delete failed');
    }

    public function change() {
        if (!rq('id') || !rq('content')) 
            return err('id and content are required');

        $comment = $this->find(rq('id'));
        if (!$comment) 
            return err('id不存在');

        if (!$comment->user_id)
            return err('匿名留言不能修改！');

        if ($comment->user_id != session('user_id')) {
            return err('你没有权限修改！');
        }

        $comment->content = rq('content');

        return $comment->update() ?
            suc(['msg' => '修改成功！']) :
            err('db update failed');
    }

    public function read() {
        // 查找单个文章的评论
        if (rq('article_id')) {
            $comments = $this->where('article_id', rq('article_id'))->get();
            if (!$comments->first())
                return err('该文章没有评论');
            return suc(['data' => $comments]);
        }

        // 查找单个用户的评论
        if (rq('user_id')) {
            $comments = $this->where('user_id', rq('user_id'))->get();
            if (!$comments->first())
                return err('该用户还没有评论');
            return suc(['data' => $comments]);
        }

        // 获取所有评论
        $comments = $this
            ->with(['user'=>function($query){
                $query->select('id','username');
            }])
            ->with(['article'=>function($query){
                $query->select('id', 'title');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return suc(['data' => $comments]);
    }

    public function user() {
        return $this->belongsTo('App\Usertable');
    }

    public function article() {
        return $this->belongsTo('App\article');
    }
}
