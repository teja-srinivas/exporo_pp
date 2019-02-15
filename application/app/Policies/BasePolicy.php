<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

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

    protected $permission;

    /**
     * BasePolicy constructor.
     *
     * @param $permission
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
    public function list(User $user)
    {
        return $this->hasPermission($user);
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
        return $this->hasPermission($user);
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
        return $this->hasPermission($user);
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
        return $this->hasPermission($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Model $model
     * @return mixed
     * @throws \Exception
     */
    public function manage(User $user, $model)
    {
        return $this->hasPermission($user);
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
        return $this->hasPermission($user);
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    protected function hasPermission(User $user): bool
    {
        return $user->can($this->permission);
    }
}
