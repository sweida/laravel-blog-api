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
        $user->name = 'admin';
        $user->email = 'admin@163.com';
        $user->is_admin = 1;
        // 密码 123456
        $user->save();
    }
}
