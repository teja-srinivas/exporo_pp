<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserDetails;
use Closure;

class UserHasFilledPersonalData
{
    const USER_HAS_MISSING_DATA = 'details.has-filled-data';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = $request->session();

        /** @var User $user */
        $user = $request->user();

        // Let internals and admins through
        if (!$user->canBeProcessed()) {
            $session->remove(self::USER_HAS_MISSING_DATA);
            return $next($request);
        }

        // Do not check anything when we're already on doing an edit
        // and remove the session hint in case customer support filled them in for us
        if ($request->routeIs('users.update') || $this->hasFilledAllData($user->details)) {
            $session->remove(self::USER_HAS_MISSING_DATA);
            return $next($request);
        }

        // In case we already redirected once, do not do so again
        if ($session->get(self::USER_HAS_MISSING_DATA)) {
            return $next($request);
        }

        $session->put(self::USER_HAS_MISSING_DATA, true);

        return redirect()->route('users.edit', $user)->withErrors($this->buildErrors($user->details, [
            'birth_date' => 'Bitte füllen Sie Ihr Geburtstag aus',
            'iban' => 'Bitte füllen Sie Ihre IBAN aus',
            'bic' => 'Bitte füllen Sie Ihre BIC aus',
        ]))->with([
            'error-message' => 'Wir benötigen noch ein paar Daten von Ihnen',
        ]);
    }

    private function hasFilledAllData(UserDetails $details): bool
    {
        if ($details === null || $details->birth_date === null || $details->birth_date === LEGACY_NULL) {
            return false;
        }

        if (empty($details->iban)) {
            return false;
        }

        return true;
    }

    /**
     * Returns only the errors for the fields the user has not filled in yet.
     *
     * @param UserDetails $details
     * @param array $array All possible errors
     * @return array Only the relevant errors
     */
    private function buildErrors(UserDetails $details, array $array): array
    {
        if ($details === null) {
            return $array;
        }

        return array_filter($array, function (string $key) use ($details) {
            return empty($details->getAttribute($key));
        }, ARRAY_FILTER_USE_KEY);
    }
}
