<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tag extends Model
{
    //查询标签
    public function read() {
        
        // 查看标签
        if (rq('id'))
        {
            // 查找指定id是否存在
            $articles = $this->where('article_id', rq('id'))->get();
            // dd($articles->toArray());
            if (!$articles)
                return err('该标签找不到文章');
            return suc(['data' => $articles]);
        }

        // 查看标签
        if (rq('tag'))
        {
            // 查找指定id是否存在
            $articles = $this->where('tag', rq('tag'))->get();
            // dd($articles->toArray());
            if (!$articles)
                return err('该标签找不到文章');
            return suc(['data' => $articles]);
        }

        $taglist =  $this
            ->groupBy('tag')
            ->pluck('tag');

        return suc(['data' => $taglist]);
    }
}
