<?php
declare(strict_types=1);

namespace App\Services;

use App\Investment;
use App\UserDetails;

final class CalculateCommissionsService
{
    const VAT = 19 / 100;

    public function calculate(Investment $investment): array
    {
        $userDetails = $investment->investor->details;
        $runtime = $investment->project->runtimeInMonths();
        $margin = $investment->project->margin;
        $bonus = $investment->is_first_investment
            ? $userDetails->first_investment_bonus
            : $userDetails->further_investment_bonus;

        $sum = $investment->project->schema->calculate((int) $investment->amount, $bonus, $runtime, (float) $margin);

        return $this->calculateNetAndGross($userDetails->vat_included, $sum);
    }

    public function calculateNetAndGross(bool $includeVat, $sum)
    {
        return $includeVat ? [
            'net' => $sum,
            'gross' => $sum * (1.0 + self::VAT),
        ] : [
            'net' => $sum * (1.0 - self::VAT),
            'gross' => $sum,
        ];
    }
}
