<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    // 软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // 接受的字段
    protected $fillable = [
        'tag', 'classify'
    ];
 
    // 数据填充时自动忽略这个字段
    public $timestamps = false;

    public function article() {
        return $this->belongsTo('App\Models\Article');
    }
}
