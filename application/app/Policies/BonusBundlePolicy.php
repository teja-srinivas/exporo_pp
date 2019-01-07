<?php

namespace App\Policies;

class BonusBundlePolicy extends BasePolicy
{
    const PERMISSION = 'manage commission bonus bundles';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
