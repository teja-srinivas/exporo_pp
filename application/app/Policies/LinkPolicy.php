<?php

declare(strict_types=1);

namespace App\Policies;

class LinkPolicy extends BasePolicy
{
    public const PERMISSION = 'management.affiliate.links';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
