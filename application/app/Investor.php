<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'first_name', 'last_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'last_user_id');
    }
}
