<?php
declare(strict_types=1);


namespace App;

use Illuminate\Database\Eloquent\Model;

class Provision extends Model
{
    protected $fillable = [
        'id', 'type_id', 'value'
    ];

    public function provisionTypes()
    {
        return $this->belongsTo(ProvisionType::class, 'type_id', 'id');
    }
}
