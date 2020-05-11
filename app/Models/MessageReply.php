<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    // 接受的字段
    protected $fillable = [
        'content', 'user_id', 'topic_user_id', 'message_id'
    ];

    // 表格隐藏的字段
    protected $hidden = [
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function topic_user()
    {
        return $this->belongsTo('App\Models\User', 'topic_user_id');
    }
}
