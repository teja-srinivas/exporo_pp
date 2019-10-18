<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserHasBeenReferred
{
    const QUERY_PARAMETER_NAME = 'ref';

    const COOKIE_NAME = 'referral_id';

    const COOKIE_LIFETIME_MINUTES = 30 * 24 * 60; // 30 days

    /**
     * Checks if we have a referral link (as a GET parameter)
     * and sets a cookie with a given lifetime.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->canBeReferred($request)) {
            Cookie::queue(cookie()->make(
                self::COOKIE_NAME,
                $request->get(self::QUERY_PARAMETER_NAME),
                self::COOKIE_LIFETIME_MINUTES
            ));
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function canBeReferred(Request $request): bool
    {
        return $request->filled(self::QUERY_PARAMETER_NAME)
            && ! $request->hasCookie(self::COOKIE_NAME);
    }
}
