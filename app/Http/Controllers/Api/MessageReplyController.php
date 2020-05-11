<?php

namespace App\Http\Controllers\Api;

use App\Models\MessageReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\MessageReplyRequest;

class MessageReplyController extends Controller
{
    // 回复留言
    public function reply(MessageReplyRequest $request){
        // 获取用户id
        $userAuth = Auth::guard('api')->user();
        $array = $request->all();
        $array['user_id'] = $userAuth['user_id'];

        MessageReply::create($array);
        return $this->message('评论成功！');
    }

    // 删除回复
    public function delete(MessageReplyRequest $request){
        $reply = MessageReply::find($request->id);
        $userAuth = Auth::guard('api')->user();

        if (!$reply['user_id'] || ($userAuth['user_id'] != $reply['user_id']))
            return $this->failed('你没有权限删除', 200);

        return $reply->delete() ?
            $this->message('留言删除成功') :
            $this->failed('留言删除失败');
    }
}
