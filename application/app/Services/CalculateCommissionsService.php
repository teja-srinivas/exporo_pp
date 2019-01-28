<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CommissionBonus;
use App\Models\Investment;
use App\Models\User;

final class CalculateCommissionsService
{
    const VAT = 1 + (19 / 100);

    public function calculate(Investment $investment, User $parent = null, User $child = null): ?array
    {
        if ($parent && $child) {
            $userId = $parent->id;
            $userDetails = $parent->details;

            $parentBonus = $this->calculateBonus($investment, $parent);

            if ($parentBonus === null) {
                return null;
            }

            $childBonus = $this->calculateBonus($investment, $child);

            if ($childBonus === null) {
                return null;
            }

            $bonus = $parentBonus - $childBonus;
        } else {
            $userId = $investment->investor->user_id;
            $userDetails = $investment->investor->details;
            $bonus = $this->calculateBonus($investment, $investment->investor->user);

            if ($bonus === null) {
                return null;
            }
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

    public function calculateNetAndGross(?bool $includeVat, float $sum): array
    {
        if ($includeVat === false) {
            return [
                'net' => $sum,
                'gross' => $sum * self::VAT,
            ];
        }

        if ($includeVat === true) {
            return [
                'net' => $sum / self::VAT,
                'gross' => $sum,
            ];
        }

        return [
            'net' => $sum,
            'gross' => $sum,
        ];
    }

    public function calculateBonus(Investment $investment, User $user): ?float
    {
        $bonuses = $user->bonuses
            // Based on the type we determine the actual value
            ->where('type_id', $investment->project->commission_type)

            // If the investor is linked to the given user, it's a direct commission
            // If not, it's an overhead and thus we always need to pick that "branch"
            //
            //               Regular   Overhead
            //     Partner     1.25       [1.5]
            //        ↑                      ↓
            //     Partner     1.25       [1.5]
            //        ↑               ↙
            //     Partner    [1.25]       1.5
            //        ↑     ↙
            //     Investor
            //
            ->where('is_overhead', $investment->investor->user_id !== $user->id);

        $bonus = $bonuses->first(function (CommissionBonus $bonus) use ($investment) {
            switch ($bonus->calculation_type) {
                case CommissionBonus::TYPE_FIRST_INVESTMENT:
                    return $investment->is_first_investment;

                case CommissionBonus::TYPE_FURTHER_INVESTMENT:
                    return !$investment->is_first_investment;
            }
        });

        return $bonus !== null ? $bonus->value : null;
    }
}
