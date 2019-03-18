<?php

namespace App\Policies;

class BannerPolicy extends BasePolicy
{
    const PERMISSION = 'management.affiliate.banners';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
