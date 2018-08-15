<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
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
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->givePermissionTo(Permission::whereIn('id', array_keys($data['permissions']))->get());

        return redirect()->route('roles.show', $role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('view', Role::class);

        $role->load(['users' => function ($q) {
            $q->latest()->with('roles');
        }]);

        return response()->view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return void
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
     * @param  \App\Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', Role::class);

        $data = $request->validate([
            'name' => 'unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        if ($role->canBeDeleted() && isset($data['name'])) {
            $role->fill(['name' => $data['name']])->save();
        }

        $role->syncPermissions(Permission::whereIn('id', array_keys($data['permissions']))->get());

        flash_success();

        return redirect()->route('roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
        return Permission::orderBy('name')->get(['id', 'name']);
    }
}
