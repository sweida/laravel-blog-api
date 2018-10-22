<?php

use Illuminate\Database\Seeder;

class articleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成15篇文章
        factory(App\article::class, 15)->create();
    }
}
