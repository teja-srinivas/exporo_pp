<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;

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
                ->join('investments', 'investments.investor_id', 'investors.id')
                ->select('investors.id', 'first_name', 'last_name', 'activation_at')
                ->selectRaw('count(investments.id) as investments')
                ->selectRaw('sum(investments.amount) as amount')
                ->groupBy('investors.id')
                ->get(),
        ]);
    }
}
