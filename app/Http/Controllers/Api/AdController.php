<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Requests\AdRequest;

class AdController extends Controller
{
    // 添加广告
    public function add(AdRequest $request){
        Ad::create($request->all());
        return $this->message('添加成功！');
    }

    //返回列表 10篇为一页
    public function list(){
        $ads = Ad::orderBy('created_at', 'desc')->paginate(10);
        return $this->success($ads);
    }

    // 修改
    public function edit(Request $request){
        Ad::findorFail($request->id)->update($request->all());
        return $this->message('修改成功');
    }

    // 删除
    public function delete(Request $request){
        Ad::findorFail($request->id)->delete();
        return $this->message('删除成功');
    }

    // 返回某个类型或者单个id的图片
    public function show(AdRequest $request){
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type)
            $ads = Ad::whereType($type)->get();
        else if ($id)
            $ads = Ad::find($id);
        else
            return $this->failed('缺少参数type或者id');

        return $this->success($ads);
    }

}
