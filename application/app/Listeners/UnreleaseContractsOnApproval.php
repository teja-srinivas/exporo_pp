<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContractUpdated;

class UnreleaseContractsOnApproval
{
    public function handle(ContractUpdated $event): void
    {
        $event->contract->user->contracts()
            ->where('type', $event->contract->type)
            ->where('id', '!=', $event->contract->id)
            ->whereNull('accepted_at')
            ->whereNotNull('released_at')
            ->update(['released_at' => null]);
    }
}
