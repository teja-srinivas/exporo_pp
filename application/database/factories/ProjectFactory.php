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
        'funding_target' => $faker->numberBetween(100000, 50000000),
        'coupon_rate' => $faker->randomFloat(2, 3, 7),
        'type' => $faker->randomElement(['Exporo Bestand', 'Exporo Financing']),
        'status' => $faker->randomElement(['in-funding', 'funded', 'coming-soon']),
        'intermediator' => $faker->company,
        'location' => $faker->city,
        'rating' => $faker->randomElement(['AA', 'A', 'B', 'C', 'D']),
        'legal_setup' => $faker->randomElement(['bond', 'investmentLaw2a']),
    ];
});

$factory->state(Project::class, 'approved', [
    'approved_at' => now(),
]);
