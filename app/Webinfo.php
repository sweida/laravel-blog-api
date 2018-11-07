<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webinfo extends Model
{
    //网站的信息
    public function setting() {
        // 检查是否第一次添加
        $webinfo = $this->find(1);
        if (!$webinfo) {
            $webinfo = $this;
        }

        if (rq('title'))
            $webinfo->title = rq('title');
        
        if (rq('keyword'))
            $webinfo->keyword = rq('keyword');

        if (rq('description'))
            $webinfo->description = rq('description');

        if (rq('startTime'))
            $webinfo->startTime = rq('startTime');

        if (rq('icp'))
            $webinfo->icp = rq('icp');

        if (rq('weixin'))
            $webinfo->weixin = rq('weixin');

        if (rq('zhifubao'))
            $webinfo->zhifubao = rq('zhifubao');

        if (rq('qq'))
            $webinfo->qq = rq('qq');

        if (rq('phone'))
            $webinfo->phone = rq('phone');

        if (rq('email'))
            $webinfo->email = rq('email');

        if (rq('github'))
            $webinfo->github = rq('github');

        if (rq('personinfo'))
            $webinfo->personinfo = rq('personinfo'); 

        return $webinfo->save() ?
            suc(['msg' => '修改成功']) :
            err('db insert failed');
    }

    public function read() {
        $webinfo = $this->find(1);
        if (!$webinfo)
            err('还没设置网站信息');
        return $webinfo?
            suc(['data' => $webinfo]):
            err('db get failed');
    }

}
