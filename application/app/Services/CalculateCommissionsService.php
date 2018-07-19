<?php
declare(strict_types=1);


namespace App\Services;

use App\Investment;
use Carbon\Carbon;

final class CalculateCommissionsService
{
    public function calculateCommission(Investment $investment, float $percentage = null): array
    {
        $schema = $investment->project->schema->first();
        $runtime = $this->calcRuntimeInMonths($investment);
        
        if ($this->checkIfIsFirstInvestment($investment)) {
            $sum = $schema->calculate((int) $investment->investsum, $investment->investor->user->details->first_investment_bonus, $runtime );
        } else {
            $sum = $schema->calculate((int) $investment->investsum, $investment->investor->user->details->further_investment_bonus , $runtime );
        }

        if($investment->investor->user->details->vat_included){
            $sums['net'] = $sum;
            $sums['gross'] = $sum * 1.19;
        }
        else{
            $sums['net'] = $sum * 0.81;
            $sums['gross'] = $sum;
        }
        
        return $sums;
    }

    private function checkIfIsFirstInvestment(Investment $investment): bool
    {
        return $investment->is_first_investment;
    }

    private function calcRuntimeInMonths(Investment $investment)
    {
        $start = $investment->project->launched_at;
        $end = Carbon::parse($investment->project->payback_min_at);
        return $end->diffInMonths($start);
    }
}
