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
        $provisionType = $investment->project->provision_type;
        $provisions = $investment->investor->user->provisions()->where('type_id', $provisionType)->first();
        $runtime = $investment->project->runtimeInMonths();
        $margin = $investment->project->margin / 100;

        if ($parent && $child) {
            $provisionsChild = $child->provisions()->where('type_id', $provisionType)->first();
            $provisionsParent = $parent->provisions()->where('type_id', $provisionType)->first();

            $userDetails = $parent->details;
            $bonus = $investment->is_first_investment
                ? $provisionsParent->first_investment - $provisionsChild->first_investment
                : $provisionsParent->further_investment - $provisionsChild->further_investment;

            $sum = $investment->project->schema->calculate((int)$investment->amount, $bonus, $runtime, (float)$margin);

            return $this->calculateNetAndGross($userDetails->vat_included, $sum);
        }

        $userDetails = $investment->investor->details;

        $bonus = $investment->is_first_investment
            ? $provisions->first_investment
            : $provisions->further_investment;

        $sum = $investment->project->schema->calculate((int)$investment->amount, $bonus, $runtime, (float)$margin);

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
