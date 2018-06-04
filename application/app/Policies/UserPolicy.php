<?php

namespace App\Policies;

use App\User;

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

    /**
     * UserPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, $model)
    {
        return $user->is($model) || parent::view($user, $model);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, $model)
    {
        return $user->is($model) || parent::update($user, $model);
    }
}
