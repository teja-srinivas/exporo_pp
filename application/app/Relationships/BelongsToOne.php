<?php

namespace App\Relationships;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToOne extends BelongsToMany
{
    public function getResults()
    {
        if ($this->parent->{$this->parentKey} === null) {
            return;
        }

        return $this->limit(1)->first();
    }
}
