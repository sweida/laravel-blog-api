<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Webinfo;
use Faker\Generator as Faker;

$factory->define(Webinfo::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'keyword' => $faker->word,
        'description' => $faker->text,
        'icp' => $faker->swiftBicNumber,
        'weixin' => $faker->imageUrl($width = 300, $height = 480),
        'zhifubao' => $faker->imageUrl($width = 300, $height = 480),
        'qq' => $faker->ean8,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->email,
        'github' => $faker->url,
        'personinfo' => $faker->text,
        'startTime' => $faker->date($format = 'Y-m-d'),
    ];
});
