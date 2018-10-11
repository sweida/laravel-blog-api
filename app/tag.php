<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tag extends Model
{
    //查询标签
    public function read() {
        
        // 查看单个文章的标签
        if (rq('article_id'))
        {
            // 查找指定id是否存在
            $article = $this->where('article_id', rq('article_id'))->get();

            if (!$article)
                return err('该标签找不到文章');
            return suc(['data' => $article]);
        }

        // 查看标签的所有文章
        if (rq('tag'))
        {
            // 查找指定id是否存在
            $articles = $this->where('tag', rq('tag'))->get();

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
