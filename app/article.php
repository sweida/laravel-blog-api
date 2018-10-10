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
        // 如有有描述就保存描述
        if (rq('content'))
            $this->content = rq('content');
            
        $aritcle = $this->save();
        // $article->tags()
        //     ->newPivotStatement()
        //     ->where('tag', rq('tag'))
        //     ->delete();

        // tags()->attach($this->id, ['tag' => rq('tag')]);

        // 将拿到的标签分割字符串
        $tagArr = explode(",",rq('tag'));

        // 将每个标签遍历插入数据库
        foreach($tagArr as $value){

            $tag = DB::table('tags')->insert([
                'tag' => $value,
                'article_id' => $this->id
            ]);
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

        $article = $this->find(rq('id'));

        if (!$article)
            return err('找不到该文章');

        if (rq('title')){
            $article->title = rq('title');
        }
        if (rq('content')){
            $article->content = rq('content');
        }

        return $article->save() ? 
            suc(['msg' => '修改成功']) :
            err('db inster failed');
    }

    // 删除文章
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
            ['status' => 1, 'msg' => '删除成功'] : 
            ['status' => 0, 'msg' => 'db delete failed'];
    }
    
    // 恢复文章
    public function restored() {
        // 检查传参是否有id
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'id is required'];

        return $this::withTrashed()->find(rq('id'))->restore() ?
            suc(['msg' => '文章已经恢复']) :
            err('db update failed');
    }

    // 获取全部文章
    public function read() {
        // 查看指定id
        if (rq('id'))
        {
            // 查找指定id是否存在
            $article = $this->find(rq('id'));
            if (!$article)
                return err('article not exists');
            return suc(['data' => $article]);
        }

        $limit = rq('limit') ?: 10;
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;
        
        $list = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get();
            // ->get(['id', 'title', 'content', 'created_at']);

        return suc(['data' => $list]);
    }        

    // 按年月查询文章
    public function times() {
        // 按年月查询
        if (!rq('year')){
            return err('year is required');
        }

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
            // var_dump($articles);
            if (!$articles->first()){
                return err('该年份没有文章');
            }
            return suc(['data' => $articles]);
        }
    }
}
