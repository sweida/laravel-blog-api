<?php

use Faker\Generator as Faker;


// 生成文章
$factory->define(App\article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->text,
        'classify' => $faker->randomElement($array = array ('前端', '后端', '工具', '随写')),
    ];
});