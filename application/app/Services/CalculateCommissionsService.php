<?php
declare(strict_types=1);

namespace App\Services;

use App\Investment;
use App\User;

final class CalculateCommissionsService
{
    const VAT = 19 / 100;

    public function calculate(Investment $investment, User $parent = null, User $child = null): array
    {
        $commissionType = $investment->project->commission_type;

        if ($parent && $child) {
            $childBonus = $child->bonuses->where('type_id', $commissionType)->first();
            $parentBonus = $parent->bonuses->where('type_id', $commissionType)->first();

            $userDetails = $parent->details;
            $bonus = $investment->is_first_investment
                ? $parentBonus->first_investment - $childBonus->first_investment
                : $parentBonus->further_investment - $childBonus->further_investment;
        } else {
            $userDetails = $investment->investor->details;
            $bonus = $investment->investor->user->bonuses->where('type_id', $commissionType)->first();
            $bonus = $investment->is_first_investment
                ? $bonus->first_investment
                : $bonus->further_investment;
        }

        $sum = $investment->project->schema->calculate([
            'investment' => (int)$investment->amount,
            'bonus' => $bonus,
            'laufzeit' => $investment->project->runtimeFactor(),
            'marge' => $investment->project->marginPercentage(),
        ]);

        return $this->calculateNetAndGross($userDetails->vat_included, $sum) + [
            'bonus' => $bonus,
        ];
    }

    public function calculateNetAndGross(bool $includeVat, float $sum): array
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
