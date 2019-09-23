<?php

use App\Models\Bill;
use App\Models\Commission;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Bill::class, static function (Faker $faker) {
    return [
        'released_at' => $faker->randomDigit > 3 ? $faker->date() : null,
    ];
});

$factory->state(Bill::class, 'released', static function (Faker $faker) {
    return [
        'released_at' => $faker->dateTimeBetween(Commission::LAUNCH_DATE)->format('Y-m-d'),
        'pdf_created_at' => $faker->dateTimeBetween(Commission::LAUNCH_DATE)->format('Y-m-d'),
    ];
});
