<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

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
        $role->givePermissionTo(Permission::query()->whereIn('id', array_keys($data['permissions']))->get());

        return redirect()->route('roles.show', $role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role $role
     * @param UserRepository $userRepository
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role, UserRepository $userRepository)
    {
        $this->authorize('view', Role::class);

        $users = $userRepository->forTableView($role->users()->getQuery());

        return response()->view('roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', Role::class);

        $permissions = $this->getPermissions();

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
        $this->authorize('update', Role::class);

        $data = $this->validate($request, [
            'name' => 'unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        if ($role->canBeDeleted() && isset($data['name'])) {
            $role->fill(['name' => $data['name']])->save();
        }

        $role->syncPermissions(Permission::query()->whereIn('id', array_keys($data['permissions']))->get());

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
        $this->authorize('delete', Role::class);

        abort_unless($role->canBeDeleted(), 409, 'Role is a system resource');

        $role->delete();

        return redirect()->route('authorization.index');
    }

    protected function getPermissions()
    {
        return Permission::query()->orderBy('name')->get(['id', 'name']);
    }
}
