<?php

namespace App\Http\Controllers\Api;

use App\Models\Webinfo;
use Illuminate\Http\Request;

class WebinfoController extends Controller
{
    // 添加和修改信息
    public function set(Request $request){
        $webinfo = Webinfo::first(); 
        if ($webinfo)
            $webinfo->update($request->all());
        else
            Webinfo::create($request->all());
        return $this->message('设置成功！');
    }

    // 获取信息
    public function read(){
        $webinfo = Webinfo::first();
        return $this->success($webinfo);
    }

}
