<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        $permissions = $this->getPermissions();

        return response()->view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $data['name']]);

        $this->syncPermissions($role,$data['permissions']);

        return redirect()->route('roles.show', $role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role $role
     * @param UserRepository $userRepository
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role, UserRepository $userRepository)
    {
        $this->authorize('view', $role);

        $users = $userRepository->forTableView($role->users()->getQuery());

        return response()->view('roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        $permissions = $this->getPermissions($role);

        return response()->view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $this->validate($request, [
            'name' => Rule::unique('roles', 'name')->ignoreModel($role),
            'permissions' => 'nullable|array',
        ]);

        if ($role->canBeDeleted() && isset($data['name'])) {
            $role->update(['name' => $data['name']]);
        }

        $this->syncPermissions($role, $data['permissions']);

        flash_success();

        return redirect()->route('roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        abort_unless($role->canBeDeleted(), Response::HTTP_FORBIDDEN, 'Role is a system resource');

        $role->delete();

        return redirect()->route('authorization.index');
    }

    protected function getPermissions(Role $allowForRole = null): Collection
    {
        return Permission::all()->reject(function (Permission $permission) use ($allowForRole) {
            return $permission->isProtected($allowForRole);
        });
    }

    protected function syncPermissions(Role $role, array $permissions)
    {
        $available = $this->getPermissions($role)->modelKeys();
        $attach = array_keys($permissions);

        $role->syncPermissions(array_intersect($available, $attach));
    }
}
