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
        $this->call(UsersTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(LinksTableSeeder::class);
        $this->call(WebinfosTableSeeder::class);
        $this->call(AdsTableSeeder::class);
    }
}
