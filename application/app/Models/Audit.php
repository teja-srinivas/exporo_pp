<?php

namespace App\Models;

use App\Traits\Dateable;

/**
 * Custom Audit implementation to support localizable Dates.
 *
 * @package App
 */
class Audit extends \OwenIt\Auditing\Models\Audit
{
    use Dateable;
}
