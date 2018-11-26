<?php

use Faker\Generator as Faker;

// 生成友情链接
$factory->define(App\link::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'href' => $faker->url,
        'desc' => $faker->sentence,
        'img' => $faker->url,
        'end_time' => $faker->date($format = 'Y-m-d'), 
    ];
});