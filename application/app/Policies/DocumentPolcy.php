<?php

namespace App\Policies;

class DocumentPolcy extends BasePolicy
{
    const PERMISSION = 'manage documents';

    /**
     * DocumentPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
