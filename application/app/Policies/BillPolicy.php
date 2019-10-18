<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class BillPolicy extends BasePolicy
{
    const PERMISSION = 'management.bills';

    const CAN_BE_BILLED_PERMISSION = 'features.bills.receive';

    const DOWNLOAD_PERMISSION = 'features.bills.download';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Bill $model
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, $model): bool
    {
        return parent::view($user, $model) || ($model->exists && $model->user_id === $user->id);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Bill $model
     * @return bool
     * @throws \Exception
     */
    public function download(User $user, $model): bool
    {
        return $user->can(self::DOWNLOAD_PERMISSION) || $model->user_id === $user->id;
    }

    /**
     * Checks whether the user can export bills.
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function export(User $user): bool
    {
        return $this->hasPermission('export', $user);
    }
}
