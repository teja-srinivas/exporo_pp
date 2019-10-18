<?php

declare(strict_types=1);

namespace App\Policies;

class BannerSetPolicy extends BasePolicy
{
    public const PERMISSION = 'management.affiliate.banner-sets';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
