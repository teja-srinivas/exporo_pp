<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

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
        $user = $request->user();

        if ($user->cancelled()) {
            return response()->view('message', [
                'message' => __('users.message.status.cancelled'),
            ]);
        }

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
