<?php

declare(strict_types=1);

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Commission::class, static function (Faker $faker) {
    $amount = $faker->numberBetween(500, 1500);

    return [
        'bill_id' => $faker->unique()->numberBetween(),
        'net' => $amount,
        'gross' => $amount,
        'bonus' => 0,
        'created_at' => $faker->dateTimeBetween('-1 years'),
    ];
});
