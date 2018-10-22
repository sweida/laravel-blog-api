<?php

use Illuminate\Database\Seeder;

class linkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成友情链接
        factory(App\link::class, 5)->create();
    }
}
