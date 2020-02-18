<?php

declare(strict_types=1);

namespace App\Policies;

class CampaignPolicy extends BasePolicy
{
    public const PERMISSION = 'management.campaigns';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
