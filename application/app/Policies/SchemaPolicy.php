<?php

namespace App\Policies;

class SchemaPolicy extends BasePolicy
{
    const PERMISSION = 'manage schemas';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
