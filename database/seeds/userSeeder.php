<?php

use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成10个用户
        factory(App\Usertable::class, 15)->create();
        $user = App\Usertable::find(1);
        $user->username = '佟丽娅';
        $user->email = '123@qq.com';
        $user->password = bcrypt('123456');
        $user->is_admin = 1;
        $user->save();

        $user2 = user_ins()->find(2);
        $user2->username = '周杰伦';
        $user2->email = '849222104@qq.com';
        $user2->save();
    }
}
