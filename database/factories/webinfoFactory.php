<?php

use Faker\Generator as Faker;

// 生成基础信息
$factory->define(App\webinfo::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'keyword' => $faker->word,
        'description' => $faker->text,
        'icp' => $faker->swiftBicNumber,
        'weixin' => $faker->url,
        'zhifubao' => $faker->url,
        'qq' => $faker->ean8,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->email,
        'github' => $faker->url,
        'personinfo' => $faker->text,
    ];
});