<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

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
    const PERMISSION = 'manage users';

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
        return $user->can('process partners');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return mixed
     * @throws \Exception
     */
    public function update(User $user, $model)
    {
        return $user->is($model) || parent::update($user, $model);
    }
}
