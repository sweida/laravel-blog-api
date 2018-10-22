<?php

use Faker\Generator as Faker;

// 生成评论
$factory->define(App\comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'article_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
