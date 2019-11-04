<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\Rules;
use Parental\HasParent;

/**
 * @property int $cancellation_days
 * @property int $claim_years The number of years since accepted_at, we can generate commissions for
 */
class PartnerContract extends Contract
{
    use HasParent;

    public const STI_TYPE = 'partner';

    public function getValidationRules(): array
    {
        return parent::getValidationRules() + Rules::byPermission([
            'management.contracts.update-cancellation-period' => [
                'cancellation_days' => ['required', 'numeric', 'min:1', 'max:365'],
            ],
            'management.contracts.update-claim' => [
                'claim_years' => ['required', 'numeric', 'min:1', 'max:7'],
            ],
        ]);
    }
}
