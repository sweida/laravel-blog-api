<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;

class TagController extends Controller
{

    public function orderByTag(TagRequest $request){
        $articles = Tag::with(['article'=>function($query){
                $query->select('id', 'title', 'img', 'clicks', 'like', 'created_at', 'classify');
            }])
            ->where('tag', $request->tag)
            ->orderBy('article_id', 'desc')
            ->paginate(10, 'article_id');

        foreach($articles as $item){
            $tag = Tag::where('article_id', $item->article_id)->get(['tag']);
            $item->article->view_count = visits($item)->count();
            // 去除重复标签
            $item->article->tag = array_values(array_unique(array_column($tag->toArray(), 'tag')));
            $item->article->commentCount = Comment::where('article_id', $item->id)->count();
        }  
        return $this->success($articles);
    }

}

