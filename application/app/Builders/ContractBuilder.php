<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Contract;

class ContractBuilder extends Builder
{

    /**
     * Only returns contracts that are active even if they have been terminated.
     * To check if a contract has not been terminated yet use isActive() on the contract itself.
     *
     * @return self
     */
    public function whereActive(): self
    {
        return $this
            ->whereNotNull('accepted_at')
            ->whereNotNull('released_at');
    }

    /**
     * Returns a map of active contracts and  their corresponding user IDs.
     *
     * @return self
     */
    public function activeByUserId(): self
    {
        return $this
            ->selectRaw('MAX(id) as id')
            ->addSelect('user_id')
            ->whereActive()
            ->groupBy('user_id');
    }

    /**
     * Adds a join that restricts the current query to only return contracts
     * which are currently active (even if they still can be terminated).
     *
     * @return ContractBuilder
     */
    public function onlyActive(): self
    {
        return $this->joinSub(
            Contract::query()->activeByUserId(),
            'contracts_active',
            'contracts_active.id',
            '=',
            'contracts.id'
        );
    }
}
