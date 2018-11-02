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

        if (request()->routeIs('users.edit')) {
            return $next($request);
        }

        if ($this->hasFilledAllData($user)) {
            return $next($request);
        }

        return redirect()->route('users.edit', $user);
    }

    private function hasFilledAllData($user): bool
    {
        if (strlen($user->salutation) > 0) {
            return true;
        }

        if (strlen($user->first_name) > 0) {
            return true;
        }

        if (strlen($user->last_name) > 0) {
            return true;
        }

        if (strlen($user->email) > 0) {
            return true;
        }

        if (strlen($user->details->birth_date) > 0 && $user->details->birth_date !== "1970-01-01 00:00:00") {
            return true;
        }

        if (strlen($user->details->phone) > 0) {
            return true;
        }

        if (strlen($user->details->address_street) > 0) {
            return true;
        }

        if (strlen($user->details->address_number) > 0) {
            return true;
        }

        if (strlen($user->details->iban) > 0) {
            return true;
        }

        return false;
    }
}
