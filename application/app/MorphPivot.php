<?php

namespace App;

use App\Traits\Dateable;

class MorphPivot extends \Illuminate\Database\Eloquent\Relations\MorphPivot
{
    use Dateable;
}
