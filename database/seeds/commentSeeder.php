<?php

use Illuminate\Database\Seeder;

class commentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成评论
        factory(App\comment::class, 30)->create();
    }
}
