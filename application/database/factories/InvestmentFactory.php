<?php

declare(strict_types=1);

use App\Models\Investment;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Investment::class, static function (Faker $faker) {
    $paidAt = $faker->randomDigit > 3 ? $faker->dateTimeBetween('-5 years') : null;

    return [
        'id' => $faker->unique()->numberBetween(),
        'amount' => $faker->numberBetween(500, 1500),
        'interest_rate' => $faker->randomFloat(2, 0.8, 1.5),
        'bonus' => $faker->numberBetween(0, 50),
        'paid_at' => $paidAt,
        'acknowledged_at' => $paidAt !== null && $faker->randomDigit > 7 ? $faker->dateTimeBetween($paidAt) : null,
        'is_first_investment' => $faker->randomDigit > 8,
        'cancelled_at' => $faker->randomDigit > 9 ? $faker->dateTime : null,
        'created_at' => $faker->dateTimeBetween('-1 years'),
    ];
});

$factory->state(Investment::class, 'paid', [
    'paid_at' => now(),
    'acknowledged_at' => now(),
]);

$factory->state(Investment::class, 'nonRefundable', [
    'paid_at' => now(),
    'acknowledged_at' => now()->subWeeks(3),
]);

$factory->state(Investment::class, 'cancelled', [
    'cancelled_at' => now(),
]);
