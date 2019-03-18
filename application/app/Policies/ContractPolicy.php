<?php

namespace App\Policies;

class ContractPolicy extends BasePolicy
{
    const PERMISSION = 'management.contracts';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
