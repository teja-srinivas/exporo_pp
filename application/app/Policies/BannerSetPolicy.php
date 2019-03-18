<?php

namespace App\Policies;

class BannerSetPolicy extends BasePolicy
{
    const PERMISSION = 'management.affiliate.banner-sets';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
