<?php

declare(strict_types=1);

use App\Models\Contract;
use Faker\Generator as Faker;
use App\Models\ContractTemplate;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Contract::class, static function (Faker $faker) {
    return [
        'template_id' => static function (): int {
            /** @var ContractTemplate $template */
            $template = factory(ContractTemplate::class)->create();

            return $template->getKey();
        },
        'cancellation_days' => 30,
        'claim_years' => 5,
    ];
});

$factory->state(Contract::class, 'active', static function (Faker $faker) {
    return [
        'accepted_at' => $faker->dateTime,
        'released_at' => $faker->dateTime,
    ];
});
