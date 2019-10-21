<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\Schema;
use App\Models\Contract;
use App\Models\CommissionType;
use App\Models\ProductContract;

trait TestsContracts
{
    protected function createSchema(): Schema
    {
        return factory(Schema::class)->create([
            'formula' => 'investment * laufzeit * marge * bonus',
        ]);
    }

    protected function createBonuses(ProductContract $contract, CommissionType $type, array $bonuses)
    {
        foreach ($bonuses as $bonus) {
            $contract->bonuses()->create($bonus + [
                'type_id' => $type->getKey(),
            ]);
        }

        $contract->refresh();
    }
}
