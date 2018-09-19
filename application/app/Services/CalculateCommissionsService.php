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
        $bonus = $investment->investor->user->bonuses()->where('type_id', $commissionType)->first();
        $runtime = $investment->project->runtimeInMonths();
        $margin = $investment->project->margin / 100;

        if ($parent && $child) {
            $childBonus = $child->bonuses()->where('type_id', $commissionType)->first();
            $parentBonus = $parent->bonuses()->where('type_id', $commissionType)->first();

            $userDetails = $parent->details;
            $bonus = $investment->is_first_investment
                ? $parentBonus->first_investment - $childBonus->first_investment
                : $parentBonus->further_investment - $childBonus->further_investment;
        } else {
            $userDetails = $investment->investor->details;

            $bonus = $investment->is_first_investment
                ? $bonus->first_investment
                : $bonus->further_investment;
        }

        $lzf = $this->calculateLZF($runtime);
        $sum = $investment->project->schema->calculate((int) $investment->amount, $bonus, $lzf, (float) $margin);

        return $this->calculateNetAndGross($userDetails->vat_included, $sum);
    }

    private function calculateLZF($runtime): float
    {
        return ($runtime / 24 < 1 ? $runtime / 24 : 1 );
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
