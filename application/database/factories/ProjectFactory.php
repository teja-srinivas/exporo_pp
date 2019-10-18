<?php

declare(strict_types=1);

use Carbon\Carbon;
use App\Models\Project;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Project::class, static function (Faker $faker) {
    $paybackMin = $faker->dateTimeBetween('now', '+4 years');

    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => $faker->streetAddress,
        'interest_rate' => $faker->randomFloat(2, 1, 4),
        'margin' => $faker->randomFloat(2, 1, 3),
        'image' => $faker->imageUrl(800, 600, 'city'),
        'description' => $faker->streetAddress,
        'launched_at' => $faker->randomDigit > 6 ? $faker->dateTimeBetween('-4 years') : null,
        'payback_min_at' => $paybackMin,
        'payback_max_at' => Carbon::parse($paybackMin)->addWeeks(2),
    ];
});

$factory->state(Project::class, 'approved', [
    'approved_at' => now(),
]);
