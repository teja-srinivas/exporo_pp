<?php

namespace App\Policies;

class BonusBundlePolicy extends BasePolicy
{
    const PERMISSION = 'management.commission-bonus-bundles';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
