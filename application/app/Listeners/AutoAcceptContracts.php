<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContractSaving;
use App\Models\PartnerContract;

class AutoAcceptContracts
{
    public function handle(ContractSaving $event)
    {
        $contract = $event->contract;

        if ($contract->accepted_at !== null) {
            return;
        }

        if (!$contract->isDirty('released_at')) {
            return;
        }

        if (
            $contract->type === PartnerContract::STI_TYPE
            && $contract->user->can('features.contracts.accept')
        ) {
            return;
        }

        $contract->accepted_at = now();
    }
}
