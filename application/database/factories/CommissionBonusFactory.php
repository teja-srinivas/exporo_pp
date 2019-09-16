<?php

use Faker\Generator as Faker;
use App\Models\CommissionType;
use App\Models\CommissionBonus;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(CommissionBonus::class, function (Faker $faker) {
    $bonus = $faker->boolean
        ? CommissionBonus::percentage(
            $faker->randomElement(CommissionBonus::TYPES),
            $faker->randomFloat(2, 0, 3),
            $faker->boolean
        )
        : CommissionBonus::value(
            $faker->randomElement(CommissionBonus::TYPES),
            $faker->randomFloat(2, 0, 3),
            $faker->boolean
        );

    return $bonus + [
        'type_id' => function () {
            /** @var CommissionType $type */
            $type = factory(CommissionType::class)->create();
            return $type->getKey();
        },
    ];
});
