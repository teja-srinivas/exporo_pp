<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AgbPolicy extends BasePolicy
{
    use HandlesAuthorization;

    const PERMISSION = 'management.agbs';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    public function attachAnyUser(\App\Models\User $user, \App\Models\Agb $model)
    {
        return $user->can('manage', $model);
    }
}
