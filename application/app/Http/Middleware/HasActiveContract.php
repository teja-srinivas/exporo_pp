<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class HasActiveContract
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

        if ($user->canBeProcessed() && !$user->hasActiveContract()) {
            return redirect()->route('contracts.confirm', $user->contract);
        }

        return $next($request);
    }
}
