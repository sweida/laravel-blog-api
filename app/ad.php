<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class ad extends Model
{
    //新增广告
    public function add() {
        if (!Request::get('title') || !Request::get('url'))
            return response()->json(
                ['status' => false, 'msg' => 'title and url are required']
            );

        $this->title = Request::get('title');
        $this->url   = Request::get('url');
        $this->type  = Request::get('type');

        return $this->save() ?
            response()->json(['status' => true, 'msg' => '新增成功']) :
            response()->json(['status' => false, 'msg' => '保存失败'], 0000);
    }

    // 修改
    public function change() {
        if (!Request::get('id'))
            return err('id is required');

        $ad = $this::find(Request::get('id'));

        if (Request::has('title'))
            $ad->title = Request::get('title');
            
        if (Request::has('url'))
            $ad->url = Request::get('url');

        if (Request::has('type'))
            $ad->type = Request::get('type');

        return $ad->update() ? 
            response()->json(['status' => true, 'msg' => '修改成功']) :
            response()->json(['status' => false, 'msg' => '保存失败'], 0000);
    }

    // 删除
    public function remove() {
        if (!Request::get('id'))
            return response()->json(['status' => false, 'msg' => 'id is required']);

        $ad = $this::find(Request::get('id'));
        if (!$ad)
            return response()->json(['status' => false, 'msg' => 'id is not find']);

        return $ad->delete() ?
            response()->json(['status' => true, 'msg' => '删除成功']) :
            response()->json(['status' => false, 'msg' => '删除失败'], 0000);
    }

    // 读取
    public function read() {

        // 查指定类型
        if (Request::get('type')){
            $ads = $this->where('type', Request::get('type'))->get();

            return $ads->first() ?
                response()->json(['status' => true, 'data' => $ads]) :
                response()->json(['status' => false, 'msg' => '该类型下无数据']);
        }

        // 查询总的
        $ads = $this
            ->orderBy('created_at')
            ->get();

        return response()->json(['status' => true, 'data' => $ads]);    
    }
}
