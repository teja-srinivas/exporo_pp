<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Nova;

/**
 * Base class that all our policies should derive from.
 *
 * Makes sure that admins can bypass all checks and adds an additional "list" check
 * for those index sites for our models (we might want to allow viewing models directly
 * but not allow listing them all).
 *
 * @package App\Policies
 */
class BasePolicy
{
    use HandlesAuthorization;

    /** @var string */
    protected $permission;

    /**
     * BasePolicy constructor.
     *
     * @param string $permission
     */
    protected function __construct(string $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Allow all actions for admin users.
     *
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }
    }

    /**
     * Determine whether the user can see a list of all models.
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function viewAny(User $user)
    {
        return $this->hasPermission('view', $user);
    }

    /**
     * Determine whether the user can view the agb.
     *
     * @param User $user
     * @param Model $model
     * @return mixed
     * @throws \Exception
     */
    public function view(User $user, $model)
    {
        return $this->hasPermission('view', $user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function create(User $user)
    {
        return $this->hasPermission('create', $user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Model $model
     * @return mixed
     * @throws \Exception
     */
    public function update(User $user, $model)
    {
        return $this->hasPermission('update', $user);
    }

    /**
     * Determine whether the user can update the model in general
     * (without any owner checks - this is for internal purposes).
     *
     * @param User $user
     * @param Model|null $model
     * @return mixed
     * @throws \Exception
     */
    public function manage(User $user, $model = null)
    {
        return $this->hasPermission('update', $user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Model $model
     * @return mixed
     * @throws \Exception
     */
    public function delete(User $user, $model)
    {
        return $this->hasPermission('delete', $user);
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    protected function hasPermission(string $action, User $user): bool
    {
        return $user->can(str_replace('manage', $action, $this->permission));
    }
}
