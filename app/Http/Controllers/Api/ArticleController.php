<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    // 添加文章
    public function add(ArticleRequest $request){

        $article = Article::create($request->all());
        
        // 将拿到的标签分割字符串
        if ($request->get('tags')){
            $tagArr = explode(",",$request->get('tags'));

            // 将每个标签遍历插入数据库
            foreach($tagArr as $tag){
                $tag = DB::table('tags')->insert([
                    'tag' => $tag,
                    'article_id' => $article->id,
                    'classify' => $article->classify
                ]);
            }
        }
        
        // 每篇文章保存一份md文档
        $this->uploadArticle($article, $request->tags);
        
        return $this->message('文章添加成功！');
    }

    //返回文章列表 10篇为一页
    public function list(Request $request){
        // 需要显示的字段
        $data = ['id', 'title', 'img', 'classify', 'clicks', 'like', 'created_at', 'deleted_at'];

        // 获取所有，包括软删除
        if($request->all)
            $articles = Article::withTrashed()->orderBy('created_at', 'desc')->paginate(10, $data);
        else if ($request->classify)
            $articles = Article::whereClassify($request->classify)->orderBy('created_at', 'desc')->paginate(10, $data);
        else
            $articles = Article::orderBy('created_at', 'desc')->paginate(10, $data);

        // 拿回文章的标签和评论总数
        foreach($articles as $item){
            $tags = Tag::where('article_id', $item->id)->get(['tag']);
            $item->view_count = visits($item)->count();
            // 去除重复标签
            $item->tags = array_values(array_unique(array_column($tags->toArray(), 'tag')));
            $item->commentCount = Comment::where('article_id', $item->id)->count();
        }  
        return $this->success($articles);
    }

    //  查看文章详情
    public function detail(ArticleRequest $request){
        $id = $request->get('id');
        if ($request->get('all'))
            // 包括下架文章
            $article = Article::withTrashed()->find($id);
        else
            $article = Article::findOrFail($id);

        // 访问统计
        visits($article)->increment();

        // 上一篇和下一篇文章
        if ($article){
            $prevId = Article::where('id', '<', $id)->max('id');
            $nextId = Article::where('id', '>', $id)->min('id');
            $article->prevArticle = Article::where('id', $prevId)->get(['id', 'title']);
            $article->nextrAticle = Article::where('id', $nextId)->get(['id', 'title']);
            $article->view_count = visits($article)->count();
            // 文章标签
            $tags = Tag::where('article_id', $id)->get(['tag']);
            $article->tags = array_column($tags->toArray(), 'tag');
            $article->comment = Comment::where('article_id', $id)->count();
        } else {
            return $this->failed('该文章已经下架');
        }
        return $this->success($article);   
    }

    // 修改文章
    public function edit(ArticleRequest $request){
        // 要可以修改下架的文章
        $article = Article::withTrashed()->findOrFail($request->id);
        $article->update($request->all());

        // 如果有修改标签
        if ($request->get('tags')){
            $this->editTag($request->id, $request->tags, $request->classify);
        }

        // 每篇文章保存一份md文档
        $this->uploadArticle($article, $request->tags);

        return $this->message('文章修改成功！');
    }

    // 修改标签
    public function editTag($id, $tags, $classify){
        // 新的标签值
        $newtags = explode(",", $tags);
        // 旧的标签值
        $oldTags = Tag::where('article_id', $id)->get(['tag']);
        $oldTags = array_column($oldTags->toArray(), 'tag');

        sort($newtags);
        sort($oldTags);

        // 如果不同
        if ($newtags != $oldTags) {
            // 先删除数据
            Tag::where('article_id', $id)->delete();

            // 再添加新的数据
            foreach($newtags as $tag){
                $tag = DB::table('tags')->insert([
                    'tag' => $tag,
                    'article_id' => $id,
                    'classify' => $classify
                ]);
            }
        }
    }

    // 下架文章
    public function delete(ArticleRequest $request){
        // 下架文章不要吧标签删除，有bug
        // Tag::where('article_id', $request->id)->delete();
        Article::findOrFail($request->id)->delete();
        return $this->message('文章下架成功');
    }

    // 恢复下架文章
    public function restored(ArticleRequest $request){
        Article::withTrashed()->findOrFail($request->id)->restore();
        Tag::withTrashed()->where('article_id', $request->id)->restore();
        return $this->message('文章恢复成功');
    }

    // 真删除文章
    public function reallyDelete(ArticleRequest $request){
        Tag::where('article_id', $request->id)->forceDelete();
        Comment::where('article_id', $request->id)->delete();
        Article::findOrFail($request->id)->forceDelete();
        return $this->success('文章删除成功');
    }

    // 点赞文章
    public function like(ArticleRequest $request) {        
        $article = Article::find($request->id);
        $article->like +=1;
        $article->save();
        return $this->message('点赞成功！');
    }

    // 获取文章所有分类及分类下的标签
    public function classify(){
        $classifys = Article::groupBy('classify')->pluck('classify');
        $classifys = array_values(array_filter($classifys->toArray()));

        for($i=0;$i<count($classifys);$i++){
            $tags = Tag::where('classify', $classifys[$i])->get(['tag']);
            
            $newArray[$i]['name'] = $classifys[$i];
            // 去重复
            $newArray[$i]['tags'] = array_unique(array_column($tags->toArray(), 'tag'));
        }
        return $this->success($newArray);
    }

    // 文章保存为md文档
    public function uploadArticle($article, $tags) {
        $id = $article->id;
        $title = $article->title;
        $classify = $article->classify;
        $time = $article->created_at;
        $text = $article->content;

        $content = "## 标题：".$title."\r\n"."> 分类：".$classify."\r\n"."> 标签：".$tags."\r\n"."> 创建时间：".$time."  \r\n\r\n".$text;
        Storage::disk('local')->put('articles/'.$id.'.md', $content);
    }

}

