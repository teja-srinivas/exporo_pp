<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * @param  User  $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(User $user)
    {
        $this->authorize('process', $user);

        $user->sendEmailVerificationNotification();

        flash_success('Mail wird verschickt');

        return back();
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function resend(Request $request)
    {
        $this->authorize('process', $request->user());

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
