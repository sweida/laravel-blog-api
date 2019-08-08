<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->numberBetween($min = 1, $max = 25),
    ];
});
