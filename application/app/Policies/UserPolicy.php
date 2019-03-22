<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for accessing user models.
 *
 * If the requested model is the currently logged in user, we will be able to view
 * and edit it regardless of our permissions (it's us, afterall).
 *
 * @package App\Policies
 */
class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    const PERMISSION = 'management.users';
    const PROCESS_PERMISSION = 'features.users.process';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * Checks if the given user can be "processed" (accepted/rejected).
     *
     * @param User $user
     * @param User|null $model
     * @return bool
     * @throws \Exception
     */
    public function process(User $user, ?User $model)
    {
        return $user->can(self::PROCESS_PERMISSION);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  User $model
     * @return mixed
     * @throws \Exception
     */
    public function update(User $user, $model)
    {
        return $user->is($model) || parent::update($user, $model);
    }

    public function attachAnyAgb(User $user, \App\Models\Agb $agb)
    {
        return $user->can('manage', $agb);
    }
}
