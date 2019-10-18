<?php

declare(strict_types=1);

use App\Models\Investor;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Investor::class, static function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->state(Investor::class, 'commissionable', [
    'claim_end' => now()->addCentury(),
]);
