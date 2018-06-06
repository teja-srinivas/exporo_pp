<?php

namespace App\Policies;

use App\User;

class RolePolicy extends BasePolicy
{
    const PERMISSION = 'manage authorization';

    /**
     * RolePolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    public function update(User $user, $model)
    {
        return $model->canBeDeleted() && parent::update($user, $model);
    }

    public function delete(User $user, $model)
    {
        return $model->canBeDeleted() && parent::delete($user, $model);
    }
}
