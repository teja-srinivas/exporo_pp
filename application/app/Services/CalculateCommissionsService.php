<?php
declare(strict_types=1);

namespace App\Services;

use App\CommissionBonus;
use App\Investment;
use App\User;

final class CalculateCommissionsService
{
    const VAT = 1 + (19 / 100);

    public function calculate(Investment $investment, User $parent = null, User $child = null): array
    {
        if ($parent && $child) {
            $userId = $parent->id;
            $userDetails = $parent->details;
            $bonus = $this->calculateBonus($investment, $parent) - $this->calculateBonus($investment, $child);
        } else {
            $userId = $investment->investor->user_id;
            $userDetails = $investment->investor->details;
            $bonus = $this->calculateBonus($investment, $investment->investor->user);
        }

        $sum = $investment->project->schema->calculate([
            'investment' => (int)$investment->amount,
            'bonus' => $bonus,
            'laufzeit' => $investment->project->runtimeFactor(),
            'marge' => $investment->project->marginPercentage(),
        ]);

        return $this->calculateNetAndGross($userDetails->vat_included, $sum) + [
            'bonus' => $bonus,
            'user_id' => $userId,
        ];
    }

    public function calculateNetAndGross(bool $includeVat, float $sum): array
    {
        return $includeVat ? [
            'net' => $sum,
            'gross' => $sum * self::VAT,
        ] : [
            'net' => $sum / self::VAT,
            'gross' => $sum,
        ];
    }

    public function calculateBonus(Investment $investment, User $user)
    {
        $bonus = optional($user->bonuses->where('type_id', $investment->project->commission_type)->first());

        switch ($bonus->calculation_type) {
            case CommissionBonus::TYPE_FIRST_INVESTMENT:
                return $investment->is_first_investment ? $bonus->value : 0;

            case CommissionBonus::TYPE_FURTHER_INVESTMENT:
                return $investment->is_first_investment ? 0 : $bonus->value;
        }
    }
}
