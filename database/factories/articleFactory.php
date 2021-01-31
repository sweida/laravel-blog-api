<?php

use App\Models\Article;
use Faker\Generator as Faker;


// 生成文章
$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->text,
        'desc' => $faker->sentence,
        'img' => $faker->imageUrl($width = 640, $height = 480),
        'like' => $faker->numberBetween($min = 20, $max = 200),
        'classify' => $faker->randomElement($array = array ('前端', '后端', '工具', '随写', '脚本')),
    ];
});