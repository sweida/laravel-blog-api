<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class article extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    // 创建文章
    public function add()
    {   
        // 检查是否有标题
        if (!rq('title'))
            return ['status' => 0, 'msg' => 'required title'];
        
        $this->title = rq('title');
        $this->content = rq('content');
        $this->classify = rq('classify');
            
        $aritcle = $this->save();

        // 将拿到的标签分割字符串
        $tag = true;
        if (rq('tag')){
            $tagArr = explode(",",rq('tag'));

            // 将每个标签遍历插入数据库
            foreach($tagArr as $value){
                $tag = DB::table('tags')->insert([
                    'tag' => $value,
                    'article_id' => $this->id
                ]);
            }
        }
            
        // 保存
        return ($aritcle && $tag) ? 
            suc(['id' => $this->id, 'msg' => '新增文章成功']) :
            err('db delete failed');
    }

    // 修改文章
    public function change() {

        if (!rq('id')){
            return err('id is required');
        }

        $article = $this::withTrashed()->find(rq('id'));

        if (!$article)
            return err('找不到该文章');

        if (rq('title'))
            $article->title = rq('title');
        if (rq('content'))
            $article->content = rq('content');
        if (rq('classify'))
            $article->classify = rq('classify');
        if (rq('created_at'))
            $article->created_at = rq('created_at');
        // 修改标签
        $tag = true;

        if (rq('tag')){
            // 新的标签值
            $newtag = explode(",",rq('tag'));

            // 旧的标签值
            $tags = tag_ins()->where('article_id', rq('id'))->get(['tag']);
            $tags= array_column($tags->toArray(), 'tag');

            sort($newtag);
            sort($tags);

            // 如果不同
            if ($newtag != $tags) {
                // 先删除数据
                tag_ins()->where('article_id', rq('id'))->delete();

                // 再添加新的数据
                foreach($newtag as $value){
                    $tag = DB::table('tags')->insert([
                        'tag' => $value,
                        'article_id' => rq('id')
                    ]);
                }
            }
        }

        return ($article->save() && $tag) ? 
            suc(['msg' => '修改成功']) :
            err('db insert failed');
    }

    // 下架文章
    public function remove()
    {
        // 检查传参是否有id
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'id is required'];
        
        $article = $this->find(rq('id'));
        // 检查传参id是否存在
        if (!$article)
            return ['status' => 0, 'msg' => 'id不存在'];

        // 软删除，永久删除用 forceDelete()
        return $article->delete() ?
            ['status' => 1, 'msg' => '下架成功'] : 
            ['status' => 0, 'msg' => 'db delete failed'];
    }
    
    // 真删除
    public function reallyDelete() {
        // 检查传参是否有id
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'id is required'];
        
        $article = $this->find(rq('id'));
        // 检查传参id是否存在
        if (!$article)
            return ['status' => 0, 'msg' => 'id不存在'];

        $tags = tag_ins()->where('article_id', rq('id'))->delete();

        return $article->forceDelete() && $tags ?
            ['status' => 1, 'msg' => '删除成功'] : 
            ['status' => 0, 'msg' => 'db delete failed'];
    }

    // 恢复文章
    public function restored() {
        // 检查传参是否有id
        if (!rq('id'))
            return err('id is required');

        return $this::withTrashed()->find(rq('id'))->restore() ?
            suc(['msg' => '文章已经恢复']) :
            err('db update failed');
    }

    // 查看文章 (包括下架的)
    public function read() {
        // 查看指定id
        if (rq('id'))
        {
            $article = $this::withTrashed()
                ->find(rq('id'));
            // 查找指定id是否存在
            if (!$article)
                return err('article not exists');
            // 浏览量
            // $article->clicks += 1;
            // $article->save();
            // 获取文章标签
            $tag = tag_ins()->where('article_id', rq('id'))->get(['tag']);
            $article->tag = array_column($tag->toArray(), 'tag');
            $article->comment = comment_ins()->where('article_id', rq('id'))->count();

            return suc(['data' => $article]);
        }

        // 分页
        $limit = rq('limit') ?: 10;
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        // 按分类获取文章
        if (rq('classify'))
        {
            $list = $this
                ->orderBy('created_at')
                ->where('classify', rq('classify'))
                ->limit($limit)
                ->skip($skip)
                ->get(['id', 'title', 'content', 'created_at', 'classify']);
            if (!$list)
                return err('该分类没有文章');

            foreach($list as $item){
                $tag = tag_ins()->where('article_id', $item->id)->get(['tag']);
                $item->tag = array_column($tag->toArray(), 'tag');
                // 评论总数
                $item->commentCount = comment_ins()->where('article_id', $item->id)->count();
            }    
            
            return suc(['classify' => rq('classify'), 'data' => $list]);
        }
        
        if (rq('all'))
        {
            // 总数量
            $total = $this::withTrashed()->count();

            // 查看所有文章 (包括下架的文章)
            $list = $this::withTrashed()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->skip($skip)
                ->get(['id', 'title', 'content', 'classify', 'created_at', 'deleted_at', 'clicks', 'like']);
        } else {
            $total = $this->count();
            // 查看所有文章
            $list = $this
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->skip($skip)
                ->get(['id', 'title', 'content', 'classify', 'created_at', 'deleted_at', 'clicks', 'like']);
        }
        // 拿回文章的标签
        foreach($list as $item){
            $tag = tag_ins()->where('article_id', $item->id)->get(['tag']);
            $item->tag = array_column($tag->toArray(), 'tag');
            $item->commentCount = comment_ins()->where('article_id', $item->id)->count();
        }  

        return suc(['data' => $list, 'total' => $total]);
    }        

    // 查看所有分类
    public function classify() {
        $classifys =  $this
            ->groupBy('classify')
            ->pluck('classify');

        return suc(['data' => $classifys]); 
    }


    // 点赞文章
    public function like() {
        if (!rq('id'))
            return err('id is required');
        
        $article = $this->find(rq('id'));
        if (!$article)
            return err('article not exists');

        $article->like +=1;

        return $article->save() ?
            suc(['msg' => '点赞成功']):
            err('db insert failed');
    }

    // 按年月查询文章
    public function times() {

        if (rq('year') && rq('month')) 
        {
            $articles = $this
                ->whereYear('created_at', rq('year'))
                ->whereMonth('created_at', rq('month'))
                ->get();
            if (!$articles->first())
                return err('该月份没有文章');
            return suc(['data' => $articles]);
        }
        
        // 按年查询
        if (rq('year')) 
        {
            $articles = $this
                ->whereYear('created_at', rq('year'))
                ->get();

            if (!$articles->first()){
                return err('该年份没有文章');
            }
            return suc(['data' => $articles]);
        }

        // 获取时间线，获取每个月份的文章数量
        $timeline =  $this
            ->groupBy('date')
            ->get([DB::raw('DATE_FORMAT(created_at, \'%Y年%m月\') as date'),DB::raw('COUNT(*) as value')])
            ->toArray();
        return suc(['data' => $timeline]);
    }

    public function tag() {
        return $this->belongsTo('App\tag');
    }

}
