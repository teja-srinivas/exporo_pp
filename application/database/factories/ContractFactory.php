<?php

use App\Models\Contract;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Contract::class, function (Faker $faker) {
    return [
        'template_id' => 0,
        'cancellation_days' => 30,
        'claim_years' => 5,
    ];
});

$factory->state(Contract::class, 'active', function (Faker $faker) {
    return [
        'accepted_at' => $faker->dateTime,
        'released_at' => $faker->dateTime,
    ];
});
