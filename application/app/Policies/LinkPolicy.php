<?php

namespace App\Policies;

class LinkPolicy extends BasePolicy
{
    const PERMISSION = 'management.links';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
