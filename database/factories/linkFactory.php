<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Link;
use Faker\Generator as Faker;

$factory->define(Link::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'url' => $faker->url,
        'desc' => $faker->sentence,
        'img' => $faker->imageUrl($width = 200, $height = 200),
    ];
});
