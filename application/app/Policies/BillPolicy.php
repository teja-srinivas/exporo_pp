<?php

namespace App\Policies;

class BillPolicy extends BasePolicy
{
    const PERMISSION = 'manage bills';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
