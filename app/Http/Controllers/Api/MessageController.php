<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    // 添加留言, 回复留言
    public function add(MessageRequest $request){
        // 获取用户id
        $userAuth = Auth::guard('api')->user();
        $array = $request->all();
        $array['user_id'] = $userAuth['user_id'];

        Message::create($array);
        return $this->message('留言成功！');
    }

    // 修改留言
    public function edit(MessageRequest $request){
        $message = Message::find($request->id);
        $userAuth = Auth::guard('api')->user();

        if (!$message['user_id'] || ($userAuth['user_id'] != $message['user_id']))
            return $this->failed('你没有权限修改');

        $message->content = $request->get('content');
        return $message->save() ? 
            $this->message('留言修改成功') :
            $this->failed('留言修改失败');
    }

    // 删除留言
    public function delete(MessageRequest $request){
        $message = Message::find($request->id);
        $userAuth = Auth::guard('api')->user();

        if (!$message['user_id'] || ($userAuth['user_id'] != $message['user_id']))
            return $this->failed('你没有权限删除', 200);

        return $message->delete() ?
            $this->message('留言删除成功') :
            $this->failed('留言删除失败');
    }

    // 批量删除
    public function deletes(Request $request){
        $ids = $request->toArray();
        if (count($ids)==0){
            return $this->failed('删除id不能为空', 200);
        } 
        Message::destroy($ids);
        return $this->message('留言删除成功');
    }

    // 获取所有留言 分页
    public function list(){
        $messages = Message::with(['user'=>function($query){
                $query->select('id','name', 'avatar_url');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->success($messages);
    }

    // 查看个人留言
    public function person(){
        $user = Auth::guard('api')->user();

        $messages = Message::where('user_id', $user['user_id'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->success($messages);
    }

}
