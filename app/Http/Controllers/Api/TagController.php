<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;

class TagController extends Controller
{

    public function orderByTag(TagRequest $request){
        // 筛选article不为null的，（null是下架的）
        $articles = Tag::whereHas('article')->with(['article'=>function($query){
                $query->select('id', 'title', 'img', 'clicks', 'like', 'created_at', 'classify');
            }])
            ->where('tag', $request->tag)
            ->orderBy('article_id', 'desc')
            ->paginate(10, 'article_id');

        if ($articles->isEmpty()){
            return $this->failed("该标签下的文章暂时下架", 200); 
        }

        // 拿回文章的标签和评论总数
        foreach($articles as $item){
            if ($item->article != null){
                $tags = Tag::where('article_id', $item->article_id)->get(['tag']);
                $item->article->view_count = visits($item->article)->count();
                // 去除重复标签
                $item->article->tags = array_values(array_unique(array_column($tags->toArray(), 'tag')));
                $item->article->commentCount = Comment::where('article_id', $item->id)->count();
            } else {
                $item = null;
            }
        }  
        return $this->success($articles);
    }

}

