<?php

declare(strict_types=1);

namespace App\Policies;

class ContractTemplatePolicy extends BasePolicy
{
    public const PERMISSION = 'management.contracts.templates';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
