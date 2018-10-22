<?php

use Illuminate\Database\Seeder;

class webinfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成基础信息
        factory(App\webinfo::class)->create();
    }
}
