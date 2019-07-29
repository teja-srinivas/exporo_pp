<?php

namespace App\Policies;

class ContractTemplatePolicy extends BasePolicy
{
    const PERMISSION = 'management.contracts.templates';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
