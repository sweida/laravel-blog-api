<?php

use App\Models\Webinfo;
use Illuminate\Database\Seeder;

class WebinfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成基础信息
        factory(Webinfo::class)->create();
    }
}
