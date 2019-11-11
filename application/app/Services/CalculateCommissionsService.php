<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Schema;
use App\Models\Investment;
use App\Models\CommissionBonus;
use App\Models\ProductContract;

final class CalculateCommissionsService
{
    /**
     * Calculates a commission entry for the given investment, parent user
     * and the actual user we do the calculation for.
     *
     * @param Investment $investment
     * @param User|null $parent
     * @param User|null $child
     * @return array|null Either the final entry array or null, if we should skip the investment altogether
     * @throws Exception
     */
    public function calculate(Investment $investment, ?User $parent = null, ?User $child = null): ?array
    {
        if ($investment->investor === null) {
            return null;
        }

        if ($parent && $child) {
            // Calculate an overhead commission
            $user = $parent;

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
            // No overhead, calculate as usual
            $user = $investment->investor->user;
            $bonus = $this->calculateBonus($investment, $user);

            if ($bonus === null) {
                return null;
            }
        }

        $sum = $this->calculateSum($investment, $bonus);

        return $this->calculateNetAndGross($user->productContract, $sum) + [
            'model_type' => Investment::MORPH_NAME,
            'model_id' => $investment->getKey(),
            'bonus' => $bonus,
            'user_id' => $user->getKey(),
        ] + ($user->canBeBilled() ? [] : [
            'on_hold' => true,
            'note_private' => 'Abrechnung gesperrt ('.now()->format('d.m.Y').')',
        ]);
    }

    /**
     * @param  Investment  $investment
     * @param  float  $bonus
     * @return float
     * @throws Exception
     */
    public function calculateSum(Investment $investment, float $bonus): float
    {
        return $investment->project->schema->calculate([
            Schema::VAR_AMOUNT => (int) $investment->amount,
            Schema::VAR_BONUS => $bonus,
            Schema::VAR_RUNTIME => $investment->project->runtimeFactor(),
            Schema::VAR_MARGIN => $investment->project->marginPercentage(),
        ]);
    }

    /**
     * Calculates the net and gross amounts based on the given sum.
     *
     * If the given contract contains no VAT details, this will simply return
     * the initial value for both, net and gross.
     *
     * @param ProductContract $contract
     * @param float $sum
     * @return array
     */
    public function calculateNetAndGross(ProductContract $contract, float $sum): array
    {
        $vatAmount = $contract->vat_amount;

        if ($vatAmount <= 0) {
            return [
                'net' => $sum,
                'gross' => $sum,
            ];
        }

        $vatAmount = 1 + ($vatAmount / 100);

        return $contract->vat_included
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
        /** @var CommissionBonus|null $bonus */
        $bonus = $user->productContract->bonuses
            ->where('type_id', $investment->project->commission_type)
            ->where('is_percentage', true)
            ->where(
                'calculation_type',
                $investment->is_first_investment
                    ? CommissionBonus::TYPE_FIRST_INVESTMENT
                    : CommissionBonus::TYPE_FURTHER_INVESTMENT
            )

            // If the investor is linked to the given user, it's a direct commission
            // If not, it's an overhead and thus we always need to pick that "branch"
            //
            //               Regular   Overhead
            //     Partner     1.25       [1.5]
            //        ↑                     ↓
            //     Partner     1.25       [1.5]
            //        ↑               ↙
            //     Partner    [1.25]       1.5
            //        ↑     ↙
            //     Investor
            ->firstWhere('is_overhead', $investment->investor->user_id !== $user->getKey());

        return $bonus !== null ? $bonus->value : null;
    }
}
