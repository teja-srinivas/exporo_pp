<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Contract;
use App\Events\ContractUpdated;

class TerminateOldContractOnApproval
{
    public function handle(ContractUpdated $event): void
    {
        if ($event->contract->accepted_at === null || !$event->contract->isDirty('accepted_at')) {
            return;
        }

        /** @var Contract|null $latest */
        $latest = $event->contract->user->contracts()
            ->whereKeyNot($event->contract->getKey())
            ->where('type', $event->contract->type)
            ->whereNotNull('accepted_at')
            ->whereNotNull('released_at')
            ->whereNull('terminated_at')
            ->latest('accepted_at')
            ->first();

        if ($latest === null) {
            return;
        }

        $latest->terminated_at = $event->contract->accepted_at;
        $latest->save();
    }
}
