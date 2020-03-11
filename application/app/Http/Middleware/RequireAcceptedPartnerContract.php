<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PartnerContract;

class RequireAcceptedPartnerContract
{
    public const SESSION_KEY = 'acceptContractLater';

    /**
     * @param  Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null || session()->has(self::SESSION_KEY)) {
            return $next($request);
        }

        $contract = $this->getReleasedPartnerContract($user);

        if ($contract === null) {
            return $next($request);
        }

        if (!$user->can('features.contracts.accept')) {
            return $next($request);
        }

        return response()->redirectToRoute('contracts.accept.index', [$contract]);
    }

    private function getReleasedPartnerContract(User $user): ?PartnerContract
    {
        if ($user->productContract === null) {
            return null;
        }

        /** @var PartnerContract $contract */
        $contract = $user->partnerContracts()
            ->whereNotNull('released_at')
            ->whereNull('accepted_at')
            ->latest()
            ->first();

        return $contract;
    }
}
