<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Commission;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user, Request $request)
    {
        $this->authorizeViewingUser($user, $request);

        $canViewUsers = Gate::allows('create', User::class);

        return view('users.users.index', [
            'user' => $user,
            'children' => $user->children()
                ->join('user_details', 'user_details.id', 'users.id')
                ->leftJoin('investors', 'investors.user_id', 'users.id')
                ->leftJoinSub(Investment::query()
                    ->select('id', 'investor_id', 'amount')
                    ->whereNotNull('investments.acknowledged_at')
                    ->uncancelled()->toBase(), 'investments', 'investments.investor_id', 'investors.id')
                ->leftJoinSub(Commission::query()
                    ->selectRaw('sum(net) as net')
                    ->selectRaw('child_user_id as user_id')
                    ->groupBy('child_user_id')
                    ->whereNotNull('bill_id')
                    ->forUser($user)->toBase(), 'commissions', 'commissions.user_id', '=', 'users.id')
                ->select('users.id', 'user_details.display_name', 'accepted_at')
                ->selectRaw('count(distinct(investments.id)) as investments')
                ->selectRaw('count(distinct(investors.id)) as investors')
                ->selectRaw('ifnull(sum(investments.amount), 0) as amount')
                ->selectRaw('ifnull(commissions.net, 0) as commissions')
                ->groupBy('users.id')
                ->get()
                ->map(static function (User $user) use ($canViewUsers) {
                    return [
                        'user' => [
                            'id' => (int) $user->getKey(),
                            'firstName' => (string) $user->display_name,
                            'lastName' => null,
                            'links' => $canViewUsers ? [
                                'self' => route('users.show', $user),
                            ] : [],
                        ],
                        'acceptedAt' => optional($user->accepted_at)->format('Y-m-d'),
                        'investments' => (int) $user->investments,
                        'investors' => (int) $user->investors,
                        'amount' => (float) $user->amount,
                        'commissions' => (float) $user->commissions,
                    ];
                }),
        ]);
    }
}
