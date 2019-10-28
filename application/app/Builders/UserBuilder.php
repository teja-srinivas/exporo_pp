<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Permission;

class UserBuilder extends Builder
{
    /** @var User */
    protected $model;

    /**
     * Only returns users that have a certain permission.
     *
     * @param  string|array|Permission|Collection  $permission
     * @return \Illuminate\Database\Eloquent\Builder|self
     */
    public function withPermission($permission)
    {
        return $this->model->scopePermission($this, $permission);
    }
}
