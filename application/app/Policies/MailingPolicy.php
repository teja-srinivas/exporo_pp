<?php

declare(strict_types=1);

namespace App\Policies;

class MailingPolicy extends BasePolicy
{
    const PERMISSION = 'management.affiliate.mailings';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
