<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use App\Models\Role;
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
     * @return self
     */
    public function withPermission($permission): self
    {
        return $this->rescope($this->model->scopePermission($this, $permission));
    }

    /**
     * Helper method to fix type safety errors.
     *
     * @param  self|\Illuminate\Database\Eloquent\Builder  $builder
     * @return self
     */
    protected function rescope(\Illuminate\Database\Eloquent\Builder $builder): self
    {
        return $builder;
    }
}
