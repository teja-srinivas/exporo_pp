<?php

declare(strict_types=1);

namespace App\Policies;

class DocumentPolicy extends BasePolicy
{
    public const PERMISSION = 'management.documents';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
