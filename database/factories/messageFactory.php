<?php

use Faker\Generator as Faker;

// 生成留言
$factory->define(App\message::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});