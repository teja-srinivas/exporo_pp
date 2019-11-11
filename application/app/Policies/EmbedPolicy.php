<?php

declare(strict_types=1);

namespace App\Policies;

class BannerPolicy extends BasePolicy
{
    public const PERMISSION = 'management.affiliate.banners';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
