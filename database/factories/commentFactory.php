<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 25),
        'article_id' => $faker->numberBetween($min = 1, $max = 25),
    ];
});
