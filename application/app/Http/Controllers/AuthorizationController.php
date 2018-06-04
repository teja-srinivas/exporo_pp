<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function index()
    {
        $this->authorize('list', Role::class);

        $roles = Role::all();
        $permissions = Permission::all();

        return response()->view('permissions.index', compact('roles', 'permissions'));
    }
}
