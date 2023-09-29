<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NewsTickers;
use Faker\Generator as Faker;

$factory->define(NewsTickers::class, function (Faker $faker) {
    return [
        'author' => $faker->name,
        'image' => 'default.png',
        'text' => $faker->text,
        'hide_ticker' => true
    ];
});
