<?php

declare(strict_types=1);

namespace App\Policies;

class BannerSetPolicy extends BasePolicy
{
    const PERMISSION = 'management.affiliate.banner-sets';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
