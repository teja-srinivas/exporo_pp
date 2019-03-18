<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /**
     * @param User $user
     * @param Request $request
     */
    protected function authorizeViewingUser(User $user, Request $request): void
    {
        $viewer = $request->user();

        abort_if($user->isNot($viewer) && $viewer->cannot('view', $user), 404);
    }
}
