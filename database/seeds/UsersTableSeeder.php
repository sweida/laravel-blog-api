<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成25个用户
        factory(User::class, 25)->create();
        $user = User::first();
        $user->name = '九歌';
        $user->email = 'weidaxyy@163.com';
        $user->is_admin = 1;
        $user->save();

        // $user2 = User::find(2);
        // $user2->name = '佟丽娅';
        // $user2->email = '849222104@qq.com';
        // $user2->save();
    }
}
