<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Investment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

final class InvestmentRepository
{
    public function getOpenForInvestor($investorId): Builder
    {
        return Investment::query()
            ->where('investor_id', $investorId)
            ->whereNull('cancelled_at');
    }

    public function withoutCommissionQuery(): Builder
    {
        return Investment::query()
            ->doesntHave('commissions')
            ->join('investors', 'investments.investor_id', 'investors.id')
            ->join('user_details', 'user_details.id', 'investors.user_id')
            ->join('users', 'investors.user_id', 'users.id')
            ->join('projects', 'investments.project_id', 'projects.id')
            ->whereNotNull('investors.user_id')
            ->whereNotNull('users.accepted_at')
            ->whereNull('users.rejected_at')
            ->whereNotNull('projects.approved_at')
            ->whereNotNull('investments.paid_at')
            ->where('investments.paid_at', '>', LEGACY_NULL)
            ->where('investments.acknowledged_at', '>', LEGACY_NULL)
            ->where('investments.acknowledged_at', '<', now()->subDays(14))
            ->select(['investments.*'])
            ->uncancelled();
    }
}
