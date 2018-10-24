<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;

class AuthorizationController extends Controller
{
    public function index()
    {
        $this->authorize('list', Role::class);

        $roles = Role::orderBy('name')->get(['id', 'name']);

        $permissions = Permission::orderBy('name')->with(['roles' => function ($q) {
            $q->orderBy('name');
        }])->get(['id', 'name']);

        return response()->view('authorization.index', compact('roles', 'permissions'));
    }
}
