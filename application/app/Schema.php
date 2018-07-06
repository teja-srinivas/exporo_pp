<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }
}
