<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Traits\Person;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class InvestorController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user, Request $request)
    {
        $this->authorizeViewingUser($user, $request);

        return view('users.investors.index', [
            'user' => $user,
            'investors' => $user->investors()
                ->leftJoinSub(Investment::query()
                    ->join('investors', 'investments.investor_id', 'investors.id')
                    ->where('user_id', $user->getKey())
                    ->whereDate('investments.acknowledged_at', '>', LEGACY_NULL)
                    ->where(function (Builder $query) {
                        $query->whereDate('investments.cancelled_at', '<=', LEGACY_NULL);
                        $query->orWhereNull('investments.cancelled_at');
                    })
                    ->addSelect('investor_id')
                    ->selectRaw('count(investments.id) as investments')
                    ->selectRaw('sum(amount) as amount')
                    ->groupBy('investor_id'), 'investments', 'investments.investor_id', '=', 'investors.id')
                ->select('investors.id', 'first_name', 'last_name', 'activation_at')
                ->selectRaw('ifnull(investments.investments, 0) as investments')
                ->selectRaw('ifnull(investments.amount, 0) as amount')
                ->get()
                ->map(function (Investor $investor) {
                    return [
                        'id' => $investor->id,
                        'name' => $investor->last_name.' '.Person::anonymizeFirstName($investor->first_name),
                        'displayName' => $investor->getAnonymousName(),
                        'investments' => (float) $investor->investments,
                        'amount' => (float) $investor->amount,
                        'activationAt' => $investor->activation_at->format('Y-m-d'),
                    ];
                }),
        ]);
    }
}
