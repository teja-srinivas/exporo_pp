<?php
declare(strict_types=1);


namespace App\Services;

use App\Investment;
use Carbon\Carbon;

final class CalculateCommissionsService
{
    public function calculateCommission(Investment $investment): array
    {
        $schema = $investment->project->schema->first();
        $runtime = $this->calcRuntimeInMonths($investment);
        if ($this->checkIfIsFirstInvestment($investment)) {
            $sum = $schema->calculate($investment->investsum, 12, $investment->investor->user->details->first_investment_bonus);
        } else {
            $sum = $schema->calculate($investment->investsum, 12, $investment->investor->user->details->further_investment_bonus);
        }
        $sums['net'] = $sum * 0.81;
        $sums['gross'] = $sum * 1.19;
        return $sums;

    }

    private function checkIfIsFirstInvestment(Investment $investment): bool
    {
        return $investment->is_first_investment;
    }

    private function getInvestmentsWithoutCommission(): Collection
    {
        return $this->investmentRepo->getInvestmentsWithoutCommission();
    }

    private function calcRuntimeInMonths(Investment $investment)
    {
        $start = $investment->project->launched_at;
        $end = Carbon::parse($investment->project->payback_min_at);
        return $end->diffInMonths($start);
    }
}
