<?php

use Faker\Generator as Faker;

// 生成标签
$factory->define(App\tag::class, function (Faker $faker) {
    return [
        'tag' => $faker->randomElement($array = array ('css','html','php', 'laravel', 'vue', 'react')),
        'article_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});