<?php

namespace App;
use Request;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    //新增留言
    public function add() {
        // 检查是否有标题
        if (!rq('content'))
            return err('留言内容不能为空！');
        
        $this->content = rq('content');

        // 如果有登录则显示登录用户；没有登陆的，如果有填用户名则显示用户名
        if (user_ins()->is_login()) {
            $this->user_id = session('user_id');
        } else {
            $this->ykname = rq('ykname');
        }
                    
        // 保存
        return $this->save() ? 
            suc(['id' => $this->id, 'msg' => '留言添加成功']) :
            err('保存失败');
    }

    // 回复留言
    public function reply() {
        if (!rq('content') || !rq('reply_id'))
            return err('回复内容和id不能为空！');

        $hasReply = $this->find(rq('reply_id'));
        if (!$hasReply) 
            return err('id不存在！');

        $this->content = rq('content');
        $this->reply_id = rq('reply_id');

        // 如果有登录则显示登录用户；没有登陆的，如果有填用户名则显示用户名
        if (user_ins()->is_login()) {
            $this->user_id = session('user_id');
        } else {
            $this->ykname = rq('ykname');
        }

        // 保存
        return $this->save() ? 
            suc(['id' => $this->id, 'msg' => '回复留言成功']) :
            err('保存失败');
    }

    //删除留言，只有登录的才能删除
    public function remove() {
        // 如果不是管理员
        if (!user_ins()->is_login())
            return err('还未登录');
            
        if (rq('id')) {
            $message = $this->find(rq('id'));
            if (!$message) 
                return err('id不存在');
        } else {
            // 如果不是管理员
            $user = user_ins()->find(session('user_id'));
            if ($user->is_admin != 1)
                return err('你没有权限删除');

            return $this->destroy(Request::all())?
                suc(['msg' => '删除成功！']) :
                err('找不到需要删除的id');
        }

        $user = user_ins()->find(session('user_id'));
        if ($user->is_admin != 1) {
            if (!$message->user_id)
                return err('匿名留言不能删除！');

            if ($message->user_id != session('user_id')) {
                return err('你没有权限删除！');
            }
        }

        return $message->delete() ?
            suc(['msg' => '删除成功！']) :
            err('db delete failed');
    }

    // 修改留言，只有登录的才能修改
    public function change() {
        if (!rq('id') || !rq('content')) {
            return err('id and content are required');
        }

        $message = $this->find(rq('id'));
        
        if (!$message) 
            return err('id不存在');

        if (!$message->user_id)
            return err('匿名留言不能修改！');

        if ($message->user_id != session('user_id')) {
            return err('你没有权限修改！');
        }
        $message->content = rq('content');

        return $message->save() ?
            suc(['msg' => '修改成功！']) :
            err('db changes failed');
    }

    // 关联表查询指定字段
    public function read() {
        // 查看指定用户
        if (rq('user_id')) {
            $user = user_ins()->find(rq('user_id'));
            if (!$user)
                return err('没有该用户');
            $messages = $this
                ->where('user_id', rq('user_id'))
                ->orderBy('created_at', 'decs')
                ->get();
            if (!$messages->first())
                return err('该用户没有留言');    

            return suc(['user' => $user->username, 'data' => $messages]);
        }

        // 分页
        $total = $this->count();
        $limit = rq('limit') ?: 10;
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        // 查看所有留言
        $messages = $this
            ->with(['user'=>function($query){
                $query->select('id','username');
             }])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->skip($skip)
            ->get();
        
        // 如果是回复的，找出回复的原文
        foreach($messages as $item) {
            $repc = $this->find($item->reply_id);
            $item->replyContent = $repc['content'];
        }

        return $messages ?
            suc(['data' => $messages, 'total' => $total]) :
            err('db get failed');
    }

    public function user() {
        return $this->belongsTo('App\Usertable');
    }
}
