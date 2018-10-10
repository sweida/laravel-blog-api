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
        $tagArr = explode(",",rq('tag'));

        foreach($tagArr as $value){

            $tag = DB::table('tags')->insert([
                'tag' => $value,
                'article_id' => $this->id
            ]);
        }
            

        // if (rq('tag'))
        //     tag_ins()->tag = rq('tag');
        //     tag_ins()->article_id = 1;
        // 保存
        return ($aritcle && $tag) ? 
            suc(['id' => $this->id, 'msg' => '新增文章成功']) :
            err('db delete failed');
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
}
