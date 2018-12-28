<?php

namespace App\Policies;

class MailingPolicy extends BasePolicy
{
    const PERMISSION = 'manage mailings';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
