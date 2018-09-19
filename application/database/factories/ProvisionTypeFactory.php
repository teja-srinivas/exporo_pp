<?php

use Faker\Generator as Faker;

$factory->define(App\CommissionType::class, function (Faker $faker) {

    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => 'finanzierung',
    ];
});
