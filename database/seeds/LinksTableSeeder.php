<?php

use App\Models\Link;
use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成友情链接
        factory(Link::class, 15)->create();
    }
}
