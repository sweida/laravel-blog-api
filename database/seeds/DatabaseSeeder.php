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
        $this->call(userSeeder::class);
        $this->call(articleSeeder::class);
        $this->call(commentSeeder::class);
        $this->call(messageSeeder::class);
        $this->call(linkSeeder::class);
        $this->call(tagSeeder::class);
        $this->call(webinfoSeeder::class);
    }
}
