<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\ProductContract;
use App\Models\ContractTemplate;
use App\Models\ProductContractTemplate;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(ProductContract::class, static function (Faker $faker) {
    return [
        'type' => ProductContract::STI_TYPE,
        'vat_amount' => $faker->randomFloat(0, 0, 19),
        'vat_included' => $faker->boolean,
        'template_id' => static function (): int {
            /** @var ContractTemplate $template */
            $template = factory(ProductContractTemplate::class)->create();

            return $template->getKey();
        },
    ];
});

$factory->state(ProductContract::class, 'active', static function (Faker $faker) {
    return [
        'accepted_at' => $faker->dateTime,
        'released_at' => $faker->dateTime,
    ];
});
