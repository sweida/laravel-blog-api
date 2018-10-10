<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // 创建问题
    public function add()
    {   
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return ['status' => 0, 'msg' => '还未登录'];

        // 检查是否有标题
        if (!rq('title'))
            return ['status' => 0, 'msg' => 'required title'];
        
        $this->title = rq('title');
        $this->user_id = session('user_id');
        // 如有有描述就保存描述
        if (rq('desc'))
            $this->desc = rq('desc');
            
        // 保存
        return $this->save() ? 
            ['status' => 1, 'id' => $this->id, 'msg' => '保存成功'] :
            ['status' => 0, 'msg' => '保存失败'];
    }

    // 修改问题
    public function change()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return ['status' => 0, 'msg' => '还未登录'];

        // 检查是否有修改的id
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'id is required'];

        $question = $this->find(rq('id'));
        // 检查id是否存在
        if (!$question)
            return ['status' => 0, 'msg' => 'id不存在'];

        // 检查该问题是否是该用户提问的
        if ($question->user_id != session('user_id'))
            return ['status' => 0, 'msg' => '你没有权限修改'];

        // 如果title和desc有修改则保存修改的
        if (rq('title'))
            $question->title = rq('title');
        if (rq('desc'))
            $question->desc = rq('desc');

        // 保存
        return $question->save() ?
            ['status' => 1, 'id' => rq('id'), 'msg' => '修改成功'] :
            ['status' => 0, 'msg' => '保存失败'];
    }

    // 查看问题
    public function read()
    {
        // 查看指定id
        if (rq('id'))
        {
            // 查找指定id是否存在
            $question = $this->find(rq('id'));
            if (!$question)
                return err('question not exists');
            return suc(['data' => $question]);
        }

        // 每页多少条
        $limit = rq('limit') ?: 10;
        // 页码，从第limit条开始
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        // 按创建时间排序
        $list = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'title', 'desc', 'user_id', 'created_at'])
            ->keyBy('id');

        // 查看所有提问，默认15条
        return ['status' => 0, 'data' => $list];
    }

    // 删除问题
    public function remove()
    {
        // 检查用户是否登陆
        if (!user_ins()->is_login())
            return ['status' => 0, 'msg' => '还未登录'];

        // 检查传参是否有id
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'id is required'];
        
        $question = $this->find(rq('id'));
        // 检查传参id是否存在
        if (!$question)
            return ['status' => 0, 'msg' => 'id不存在'];
        // 检查问题是否提问者
        if (session('user_id') != $question->user_id)
            return ['status' => 0, 'msg' => '你没有权限删除'];

        // 删除
        return $question->delete() ?
            ['status' => 1, 'msg' => '删除成功'] : 
            ['status' => 0, 'msg' => 'db delete failed'];
    }
}
