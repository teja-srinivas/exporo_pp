<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;

class AuthorizationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::query()->orderBy('name')->get(['id', 'name']);

        $permissions = Permission::query()
            ->with('roles')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->view('authorization.index', compact('roles', 'permissions'));
    }
}
