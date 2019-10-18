<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use App\Models\Role;
use Illuminate\View\View;

class UserTableComposer
{
    public function compose(View $view)
    {
        $view->with('roles', Role::all()->map(static function (Role $role) {
            return [
                'id' => $role->id,
                'link' => route('roles.show', $role),
                'name' => $role->getDisplayName(),
                'color' => $role->getColor(),
            ];
        }));
    }
}
