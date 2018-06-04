<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class UserIsAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->rejected()) {
            return response()->view('message', [
                'message' => __('users.message.status.rejected'),
            ]);
        }

        if ($user->notYetAccepted()) {
            return response()->view('message', [
                'message' => __('users.message.status.idle'),
            ]);
        }

        return $next($request);
    }
}
