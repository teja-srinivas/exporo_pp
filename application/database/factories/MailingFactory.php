<?php

use App\Models\Mailing;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Mailing::class, static function (Faker $faker) {
    return [
        'title' => $faker->words(3, true),
        'text' => $faker->text,
        'html' => $faker->randomHtml(1),
    ];
});
