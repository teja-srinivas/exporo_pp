<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    public $incrementing = false;


    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id');
    }
}
