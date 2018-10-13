<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad extends Model
{
    //新增广告
    public function add() {
        if (!rq('title') || !rq('url'))
            return err('title and url are required');

        $this->title = rq('title');
        $this->url = rq('url');
        $this->type = rq('type');

        return $this->save() ?
            suc(['msg' => '新增成功']) :
            err('db insert failed');
    }

    // 修改
    public function change() {
        if (!rq('id'))
            return err('id is required');

        $ad = $this::find(rq('id'));

        if (rq('title'))
            $ad->title = rq('title');
            
        if (rq('url'))
            $ad->url = rq('url');

        if (rq('type'))
            $ad->type = rq('type');

        return $ad->update() ? 
            suc(['msg' => '修改成功']) :
            err('db change failed');
    }

    // 删除
    public function remove() {
        if (!rq('id'))
            return err('id is required');

        $ad = $this::find(rq('id'));
        if (!$ad)
            return err('id 不存在');

        return $ad->delete() ?
            suc(['msg' => '删除成功']) :
            err('db delete failed');
    }

    // 读取
    public function read() {

        if (rq('type')){
            $ads = $this
                ->where('type', rq('type'))
                ->get();

            return $ads->first() ?
                suc(['data' => $ads]) :
                err('db get failed');
        }

        $ads = $this
            ->orderBy('created_at')
            ->get();

        return $ads->first() ?
            suc(['data' => $ads]) :
            err('db get failed');    
    }
}
