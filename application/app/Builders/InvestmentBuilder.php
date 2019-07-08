<?php

declare(strict_types=1);

namespace App\Builders;

class InvestmentBuilder extends Builder
{
    use Traits\HasUser;

    /**
     * Only returns uncancelled investments.
     *
     * @return self
     */
    public function uncancelled(): self
    {
        return $this->whereNull('investments.cancelled_at');
    }

    public function projectGotApproved(): self
    {
        return $this
            ->join('projects', 'investments.project_id', 'projects.id')
            ->whereNotNull('projects.approved_at');
    }

    public function refundable(): self
    {
        return $this->where('acknowledged_at', '<=', now()->subWeeks(2));
    }

    public function nonRefundable(): self
    {
        return $this
            ->whereNotNull('investments.acknowledged_at')
            ->where('investments.acknowledged_at', '<', now()->subDays(14));
    }

    /**
     * @return self
     */
    public function withoutCommissions(): self
    {
        return $this
            ->select(['investments.*'])
            ->doesntHave('commissions')
            ->join('investors', 'investments.investor_id', 'investors.id')
            // TODO ->join('user_details', 'user_details.id', 'investors.user_id')
            ->whereNotNull('investors.user_id')
            ->whereNotNull('investments.paid_at')
            ->whereHasActiveUser('investors')
            ->projectGotApproved()
            ->nonRefundable()
            ->uncancelled();
    }
}
