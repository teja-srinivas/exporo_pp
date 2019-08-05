<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contract;

class ContractPolicy extends BasePolicy
{
    const PERMISSION = 'management.contracts';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * @param User $user
     * @param Contract $model
     * @return mixed
     * @throws \Exception
     */
    public function view(User $user, $model)
    {
        return $model->user->is($user) || parent::view($user, $model);
    }
}
