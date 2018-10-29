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

    public function queryWithoutCommission(): Builder
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
            ->select(['investments.*']);
    }

    public function queryWhereCalculationChanged(): Builder
    {
        return Investment::query()
            ->select(['investments.*'])
            ->leftJoin('projects', 'investments.project_id', 'projects.id')
            ->leftJoin('schemas', 'projects.schema_id', 'schemas.id')
            ->leftJoin('investors', 'investments.investor_id', 'investors.id')
            ->leftJoin('commissions', function (JoinClause $join) {
                $join->on('investments.id', '=', 'commissions.model_id')
                    ->where('commissions.model_type', '=', 'investment');
            })
            ->whereColumn('commissions.updated_at', '<', 'investments.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'projects.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'schemas.updated_at')
            ->orWhereColumn('commissions.updated_at', '<', 'investors.updated_at');
    }
}
