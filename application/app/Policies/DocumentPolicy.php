<?php

namespace App\Policies;

class DocumentPolicy extends BasePolicy
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
