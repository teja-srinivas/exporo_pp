<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(),
        'client_id' => $faker->unique()->numberBetween(),
        'name' => $faker->streetAddress,
        'interest_rate' => $faker->randomFloat(2, 1, 4),
        'term' => '',
        'margin' => $faker->randomFloat(2, 1, 3),
        'type' => $faker->word,
        'image' => $faker->imageUrl(800, 600, 'city'),
        'description' => $faker->text,
        'scheme_id' => $faker->numberBetween(1, 5),
    ];
});
