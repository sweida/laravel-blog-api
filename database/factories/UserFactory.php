<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,     // unique 唯一值
        'password' => '123456',      // 模型里的接受字段已经加密过了，所以这里不需要
        'avatar_url' => $faker->imageUrl($width = 200, $height = 200),
        // 'password' => bcrypt('123456'), // secret
    ];
});
