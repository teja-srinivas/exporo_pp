<?php

namespace App\Policies;

class DocumentPolicy extends BasePolicy
{
    const PERMISSION = 'manage documents';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
