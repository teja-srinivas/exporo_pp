<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class UserHasFilledPersonalData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        if (request()->routeIs('users.edit', 'users.update')) {
            return $next($request);
        }

        if ($this->hasFilledAllData($user)) {
            return $next($request);
        }

        return redirect()->route('users.edit', $user);
    }

    private function hasFilledAllData($user): bool
    {
        if ($user->details->birth_date === "1970-01-01 00:00:00") {
            return false;
        }

        if (strlen($user->details->iban) === 0) {
            return false;
        }

        return true;
    }
}
