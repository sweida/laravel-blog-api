<?php

namespace App;

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

    //删除留言，只有登录的才能删除
    public function remove() {
        if (!rq('id')) {
            return err('id is required');
        }

        $message = $this->find(rq('id'));
        if (!$message) 
            return err('id不存在');

        if (!$message->user_id)
            return err('匿名留言不能删除！');

        if ($message->user_id != session('user_id')) {
            return err('你没有权限删除！');
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
        $message->content = rq('content');
        if (!$message) 
            return err('id不存在');

        if (!$message->user_id)
            return err('匿名留言不能修改！');

        if ($message->user_id != session('user_id')) {
            return err('你没有权限修改！');
        }

        return $message->save() ?
            suc(['msg' => '修改成功！']) :
            err('db changes failed');
    }

    // 关联表查询指定字段
    public function read() {
        $messages = $this
            ->with(['user'=>function($query){
                $query->select('id','username');
             }])
            ->orderBy('created_at')
            ->get();

        return $messages ?
            suc(['data' => $messages]) :
            err('db get failed');
    }

    public function user() {
        return $this->belongsTo('App\Usertable');
    }
}
