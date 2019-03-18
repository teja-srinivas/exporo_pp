<?php

namespace App\Policies;

class SchemaPolicy extends BasePolicy
{
    const PERMISSION = 'management.schemas';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
