<?php

use Carbon\Carbon;
use App\Models\Project;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Project::class, function (Faker $faker) {
    $paybackMin = $faker->date();

    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => $faker->streetAddress,
        'interest_rate' => $faker->randomFloat(2, 1, 4),
        'margin' => $faker->randomFloat(2, 1, 3),
        'image' => $faker->imageUrl(800, 600, 'city'),
        'description' => $faker->text,
        'launched_at' => $faker->randomDigit > 6 ? $faker->date() : null,
        'payback_min_at' => $paybackMin,
        'payback_max_at' => Carbon::parse($paybackMin)->addWeeks(2),
    ];
});

$factory->state(Project::class, 'approved', [
    'approved_at' => now(),
]);
