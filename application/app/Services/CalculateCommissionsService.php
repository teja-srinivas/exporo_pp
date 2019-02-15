<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CommissionBonus;
use App\Models\Investment;
use App\Models\User;
use App\Models\UserDetails;
use App\Policies\BillPolicy;

final class CalculateCommissionsService
{
    /**
     * Calculates a commission entry for the given investment, parent user
     * and the actual user we do the calculation for.
     *
     * @param Investment $investment
     * @param User|null $parent
     * @param User|null $child
     * @return array|null Either the final entry array or null, if we should skip the investment alltogether
     * @throws \Exception
     */
    public function calculate(Investment $investment, User $parent = null, User $child = null): ?array
    {
        if ($parent && $child) {
            // Calculate an overhead commission
            $user = $parent;

            $parentBonus = $this->calculateBonus($investment, $parent);
            if ($parentBonus === null) return null;

            $childBonus = $this->calculateBonus($investment, $child);
            if ($childBonus === null) return null;

            $bonus = $parentBonus - $childBonus;
        } else {
            // No overhead, calculate as usual
            $user = $investment->investor->user;
            $bonus = $this->calculateBonus($investment, $user);

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

        return $this->calculateNetAndGross($user->details, $sum) + [
            'bonus' => $bonus,
            'user_id' => $user->getKey(),
        ] + ($user->canBeBilled() ? [] : [
            'on_hold' => true,
            'note_private' => 'Abrechnung gesperrt (' . now()->format('d.m.Y') . ')',
        ]);
    }

    public function calculateNetAndGross(UserDetails $details, float $sum): array
    {
        $vatAmount = $details->vat_amount;

        if ($vatAmount <= 0) {
            return [
                'net' => $sum,
                'gross' => $sum,
            ];
        }

        $vatAmount = 1 + ($vatAmount / 100);

        return $details->vat_included
            ? [
                'net' => $sum / $vatAmount,
                'gross' => $sum,
            ]
            : [
                'net' => $sum,
                'gross' => $sum * $vatAmount,
            ];
    }

    /**
     * Calculates the bonus value or percentage for the given investment and user.
     *
     * @param Investment $investment The investment we get the commission type from
     * @param User $user The user we grab the bonus entry from
     * @return float|null Either a value or null if there's no bonus.
     *                    Yes, there can be values of 0 for which we should create a commission entry.
     */
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
