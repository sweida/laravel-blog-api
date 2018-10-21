<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // 生成10个用户
        factory(\App\Usertable::class, 10)->create();
        $user = \App\Usertable::find(1);
        $user->username = '佟丽娅';
        $user->email = '123@qq.com';
        $user->password = bcrypt('123456');
        $user->is_admin = 1;
        $user->save();

        // 生成20篇文章
        factory(\App\article::class, 20)->create();

        // 生成友情链接
        factory(\App\link::class, 5)->create();

        // 生成基础信息
        factory(\App\webinfo::class)->create();

        // 生成留言
        factory(\App\message::class, 10)->create();

        // 生成评论
        factory(\App\comment::class, 20)->create();
    }
}
