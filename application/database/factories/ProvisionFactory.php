<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\CommissionBonus::class, function (Faker $faker) {

    return [
        'id' => $faker->unique()->numberBetween(),
        'type_id' => $faker->randomNumber(),
        'registration' => $faker->randomNumber(),
    ];
});
