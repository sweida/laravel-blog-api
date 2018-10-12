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
        factory(\App\Usertable::class, 10)->create();
        $user = \App\Usertable::find(1);
        $user->username = '佟丽娅';
        $user->email = '123@qq.com';
        $user->password = bcrypt('123456');
        $user->isadmin = 1;
        $user->save();
    }
}
