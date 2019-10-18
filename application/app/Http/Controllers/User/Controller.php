<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

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
