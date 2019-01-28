<?php

namespace App\Policies;

use App\Models\User;

class BillPolicy extends BasePolicy
{
    const PERMISSION = 'manage bills';
    const CAN_BE_BILLED_PERMISSION = 'can be billed';
    const DOWNLOAD_PERMISSION = 'download bills';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Bill $model
     * @return mixed
     * @throws \Exception
     */
    public function view(User $user, $model)
    {
        return parent::view($user, $model) || $model->user_id === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Bill $model
     * @return mixed
     * @throws \Exception
     */
    public function download(User $user, $model)
    {
        return $user->hasPermissionTo(self::DOWNLOAD_PERMISSION) || $model->user_id === $user->id;
    }
}
