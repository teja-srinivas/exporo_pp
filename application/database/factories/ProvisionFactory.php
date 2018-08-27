<?php

use Faker\Generator as Faker;

$factory->define(App\Provision::class, function (Faker $faker) {

    return [
        'id' => $faker->unique()->numberBetween(),
        'type_id' => $faker->randomNumber(),
        'user_id' => $faker->randomNumber(),
        'first_investment' => $faker->randomNumber(),
        'further_investment' => $faker->randomNumber(),
        'registration' => $faker->randomNumber(),
    ];
});
