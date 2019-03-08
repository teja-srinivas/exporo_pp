<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;

class AuthorizationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::query()->orderBy('name')->get(['id', 'name']);

        $permissions = Permission::query()
            ->orderBy('name')
            ->with('roles')
            ->get(['id', 'name']);

        return response()->view('authorization.index', compact('roles', 'permissions'));
    }
}
