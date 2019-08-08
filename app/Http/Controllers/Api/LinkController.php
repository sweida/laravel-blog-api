<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Requests\LinkRequest;

class LinkController extends Controller
{
    // 添加链接
    public function add(LinkRequest $request){
        Link::create($request->all());
        return $this->message('添加成功');
    }

    // 修改链接
    public function edit(LinkRequest $request){
        $link = Link::findOrFail($request->id);
        $link->update($request->all());
        return $this->message('修改成功！');
    }

    // 删除链接
    public function delete(LinkRequest $request){
        $link = Link::find($request->id);

        return $link->delete() ?
            $this->message('删除成功') :
            $this->failed('删除失败');
    }

    // 获取所有链接 分页
    public function list(){
        $links = Link::paginate(18);
        return $this->success($links);
    }


}
