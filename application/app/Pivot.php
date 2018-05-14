<?php

namespace App;

use App\Traits\Dateable;

class Pivot extends \Illuminate\Database\Eloquent\Relations\Pivot
{
    use Dateable;
}
