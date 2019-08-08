<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Member;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function testRedis()
    {
        Redis::set('name', 'guwenjie');
        $values = Redis::get('name');
        
        $mkv = array(
            'usr:0001' => 'First user',
            'usr:0002' => 'Second user',
            'usr:0003' => 'Third user'
        );
        Redis::mset($mkv);
        // dd($values);
        //输出："guwenjie"
        //加一个小例子比如网站首页某个人员或者某条新闻日访问量特别高，可以存储进redis，减轻内存压力
        // $userinfo = Member::find(1200);
        // Redis::set('user_key',$userinfo);
        // if(Redis::exists('user_key')){
        //     $values = Redis::get('user_key');
        // }else{
        //     $values = Member::find(1200);//此处为了测试你可以将id=1200改为另一个id
        //  }
        // dump($values);
    }

    public function set()
    {
        Redis::set('name', 'sean');
        return ['msg' => '设置成功'];
    }

    public function get()
    {
        $name = Redis::get('name');
        var_dump($name);
    }

    public function del(){
        Redis::del('name');
    }

}
