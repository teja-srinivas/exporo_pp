<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;

class PartnerContract extends Contract
{
    use HasParent;

    public const STI_TYPE = 'partner';
}
