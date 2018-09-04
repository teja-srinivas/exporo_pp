<?php
declare(strict_types=1);


namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvisionType extends Model
{
    protected $fillable = [
        'id', 'user_id', 'name'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'provision_type');
    }

    public function provisions()
    {
        return $this->hasOne(Provision::class, 'type_id', 'id');
    }
}
