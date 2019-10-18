<?php

declare(strict_types=1);

namespace App\Policies;

class SchemaPolicy extends BasePolicy
{
    public const PERMISSION = 'management.schemas';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
