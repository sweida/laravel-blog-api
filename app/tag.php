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
            $article = $this->where('article_id', rq('article_id'))->get(['tag']);

            if (!$article->first())
                return err('该文章没有标签');
            return suc(['article_id' => rq('article_id'), 'data' => $article]);
        }

        // 查看标签的所有文章
        if (rq('tag'))
        {
            // 查找指定id是否存在 (拿到文章详情)
            $articles = $this
                // ->orderBy('created_at', 'desc')
                ->with(['article'=>function($query){
                    $query->select('id', 'title', 'img', 'clicks', 'created_at', 'classify');
                 }])
                ->where('tag', rq('tag'))
                ->orderBy('article_id', 'desc')
                ->get(['article_id']);
        
            // 返回没有删除的文章
            $articles = $articles->where('article','!=', null);

            if (!$articles->first())
                return err('该标签找不到文章');

            // 拿回每篇文章的所有标签和评论总数
            foreach($articles as $item){
                $tag = $this->where('article_id', $item->article_id)->get(['tag']);
                $item->article->tag = array_column($tag->toArray(), 'tag');
                $item->article->commentCount = comment_ins()->where('article_id', $item->article_id)->count();
            } 
            return suc(['tag' => rq('tag'), 'data' => $articles]);
        }

        $taglist =  $this
            ->groupBy('tag')
            ->pluck('tag');

        return suc(['data' => $taglist]);
    }

    // 关联articles表
    public function article() {
        return $this->belongsTo('App\article');
    }

    // 数据填充时会生成时间字段，设置false禁止操作该字段
    public $timestamps = false;
}
