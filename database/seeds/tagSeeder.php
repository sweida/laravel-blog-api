<?php

use Illuminate\Database\Seeder;

class tagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成文章标签
        factory(App\tag::class, 30)->create();
    }
}
