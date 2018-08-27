<?php
declare(strict_types=1);


namespace App;

use Illuminate\Database\Eloquent\Model;

class Provision extends Model
{
    protected $fillable = [
        'id', 'type_id', 'first_investment', 'further_investment', 'registration', 'user_id'
    ];

    public function provisionTypes()
    {
        return $this->belongsTo(ProvisionType::class, 'type_id', 'id');
    }

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
        'first_investment' => 'float',
        'further_investment' => 'float',
        'registration' => 'float'
    ];
}
