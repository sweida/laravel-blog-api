<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

// 生成用户
$factory->define(App\Usertable::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,     // unique 唯一值
        'password' => bcrypt('123456'), // secret
    ];
});

// 生成文章
$factory->define(App\article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->text,
        'classify' => $faker->randomElement($array = array ('前端', '后端', '工具', '随写')),
    ];
});

// 生成标签
$factory->define(App\tag::class, function (Faker $faker) {
    return [
        'tag' => $faker->randomElement($array = array ('css','html','php', 'laravle', 'vue', 'react')),
        'article_id' => $faker->numberBetween($min = 1, $max = 15),
    ];
});
        
// 生成友情链接
$factory->define(App\link::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'href' => $faker->url,
        'end_time' => $faker->date($format = 'Y-m-d'), 
    ];
});

// 生成基础信息
$factory->define(App\webinfo::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'keyword' => $faker->word,
        'description' => $faker->text,
        'icp' => $faker->swiftBicNumber,
        'weixin' => $faker->url,
        'zhifubao' => $faker->url,
        'qq' => $faker->ean8,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->email,
        'github' => $faker->url,
        'personinfo' => $faker->text,
    ];
});

// 生成留言
$factory->define(App\message::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});

// 生成评论
$factory->define(App\comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'article_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});