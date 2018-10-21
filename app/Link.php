<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //添加友情连接
    public function add() {
        if (!rq('title') || !rq('href')) {
            return err('title or href are required');
        }

        if (rq('title') && rq('href')){
            $this->title = rq('title');
            $this->href = rq('href');
            $this->start_time = rq('start_time');
            $this->end_time = rq('end_time');
        }

        return $this->save() ?
            suc(['msg' => '添加成功']) :
            err('db insert failed');
    }

    // 修改连接
    public function change() {
        if (!rq('id')) {
            return err('id is required');
        }

        $link = $this->find(rq('id'));
        if (!$link)
            return err('找不到该id');

        if (rq('title'))
            $link->title = rq('title');

        if (rq('href'))
            $link->href = rq('href');

        if (rq('end_time'))
            $link->end_time = rq('end_time');

        return $link->save() ?
            suc(['msg' => '修改成功']) :
            err('db change failed');
    }

    // 删除连接
    public function remove() {
        if (!rq('id'))
            return err('id is required');

        $link = $this->find(rq('id'));
        if (!$link)
            return err('找不到该id');
        
        return $link->delete() ?
            suc(['msg' => '删除成功']) :
            err('db delete failed');
    }

    // 获取全部友情连接，（只获取有效期内的，或者没有设置有效期的）
    public function read() {
        if (rq('all')) {
            $links = $this
                ->orderBy('created_at')
                ->get(['id', 'title', 'href', 'end_time']);
        } else {
            $links = $this
                ->orderBy('created_at')
                ->whereDate('end_time', '>=', date('Y-m-d',time()))
                ->orwhere('end_time', null)
                ->get(['id', 'title', 'href']);
        }

        return $links ?
            suc(['data' => $links]) :
            err('db get failed');   
    }
}
