<?php

namespace App\Policies;

use Exception;
use App\Models\User;
use App\Models\CommissionType;

class CommissionTypePolicy extends BasePolicy
{
    const PERMISSION = 'management.commission-types';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * @param  User  $user
     * @param  CommissionType  $model
     * @return bool|mixed
     * @throws Exception
     */
    public function delete(User $user, $model)
    {
        return parent::delete($user, $model) && $model->projects()->doesntExist();
    }
}
