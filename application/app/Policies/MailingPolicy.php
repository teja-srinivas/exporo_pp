<?php

namespace App\Policies;

class MailingPolicy extends BasePolicy
{
    const PERMISSION = 'management.mailings';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
