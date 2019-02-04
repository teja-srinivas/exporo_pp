<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class HasSelectedBundle
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

        if ($user->canBeProcessed() && !$user->hasBundleSelected()) {
            return redirect()->route('users.bundle-selection.index', $user);
        }

        return $next($request);
    }
}
