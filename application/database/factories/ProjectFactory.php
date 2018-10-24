<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Project::class, function (Faker $faker) {
    $paybackMin = $faker->date();
    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => $faker->streetAddress,
        'interest_rate' => $faker->randomFloat(2, 1, 4),
        'margin' => $faker->randomFloat(2, 1, 3),
        'type' => $faker->word,
        'image' => $faker->imageUrl(800, 600, 'city'),
        'description' => $faker->text,
        'capital_cost' => $faker->randomFloat(2, 1000, 2000),
        'launched_at' => $faker->randomDigit > 6 ? $faker->date() : null,
        'payback_min_at' => $paybackMin,
        'payback_max_at' => \Carbon\Carbon::parse($paybackMin)->addWeeks(2),
    ];
});
