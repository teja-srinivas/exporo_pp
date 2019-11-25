<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Contract;
use App\Models\Investor;
use App\Models\CommissionBonus;
use App\Models\ProductContract;
use Illuminate\Database\Query\JoinClause;

class InvestorBuilder extends Builder
{
    use Traits\HasUser;

    /**
     * Only returns investors that meet the criteria for which
     * we're allowed to calculate commissions for.
     *
     * @return InvestorBuilder
     */
    public function commissionable(): self
    {
        // Make sure we only get what we need
        if ($this->query->columns === []) {
            $this->select('investors.*');
        }

        return $this
            ->joinActiveUserContract(ProductContract::class)
            ->whereDoesntHaveCommissions()
            ->whereHasActiveUser()
            ->whereClaimIsActive()

            // Check for an active bonus
            ->leftJoin('commission_bonuses', 'commission_bonuses.contract_id', 'contracts.id')
            ->where('commission_bonuses.calculation_type', CommissionBonus::TYPE_REGISTRATION)
            ->where('commission_bonuses.value', '>', 0);
    }

    /**
     * Only returns investors that we have not created any commissions for yet.
     *
     * @return self
     */
    public function whereDoesntHaveCommissions(): self
    {
        return $this
            ->whereNull('commissions.id')
            ->leftJoin('commissions', static function (JoinClause $join) {
                $join->on('investors.id', 'commissions.model_id');
                $join->where('commissions.model_type', Investor::MORPH_NAME);
            });
    }

    /**
     * Checks if we're within "claim range".
     *
     * @return self
     */
    public function whereClaimIsActive(): self
    {
        return $this->where('investors.claim_end', '>', now());
    }

    /**
     * Adds the active partner user contract to the statement.
     *
     * @param  string  $type
     * @return self
     */
    protected function joinActiveUserContract(string $type): self
    {
        $active = Contract::query()
            ->whereType(Contract::getTypeForClass($type))
            ->addSelect('contracts.id')
            ->addSelect('contracts.user_id')
            ->addSelect('vat_amount')
            ->addSelect('vat_included')
            ->onlyActive()
            ->toBase();

        return $this->joinSub($active, 'contracts', 'contracts.user_id', '=', 'investors.user_id');
    }
}
