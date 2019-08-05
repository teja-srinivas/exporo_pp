<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use App\Models\Schema;
use App\Models\Contract;
use App\Models\CommissionType;

trait TestsContracts
{
    protected function createSchema(): Schema
    {
        return factory(Schema::class)->create([
            'formula' => 'investment * laufzeit * marge * bonus',
        ]);
    }

    protected function createBonuses(Contract $contract, CommissionType $type, array $bonuses)
    {
        foreach ($bonuses as $bonus) {
            $contract->bonuses()->create($bonus + [
                'type_id' => $type->getKey(),
            ]);
        }

        $contract->refresh();
    }
}