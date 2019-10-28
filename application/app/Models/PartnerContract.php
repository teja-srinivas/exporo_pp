<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;

/**
 * @property int $cancellation_days
 * @property int $claim_years The number of years since accepted_at, we can generate commissions for
 */
class PartnerContract extends Contract
{
    use HasParent;

    public const STI_TYPE = 'partner';
}
