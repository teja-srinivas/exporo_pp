<?php

namespace App\Http\ViewComposers;

use App\Models\Role;
use Illuminate\View\View;

class UserTableComposer
{
    public function compose(View $view)
    {
        $view->with('roles', Role::all()->map(function (Role $role) {
            return [
                'id' => $role->id,
                'link' => route('roles.show', $role),
                'name' => studly_case($role->name),
                'color' => $role->getColor(),
            ];
        }));
    }
}
