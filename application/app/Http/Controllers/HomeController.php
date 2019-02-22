<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Collection $bills */
        $bills = Bill::getDetailsPerUser($user->id)->released()->visible()->latest()->get();

        return response()->view('home', [
            'bills' => $bills,

            // Stats
            'pending' => Commission::query()
                ->whereNull('reviewed_at')
                ->whereNull('rejected_at')
                ->whereNull('bill_id')
                ->forUser($user)
                ->afterLaunch()
                ->sum('gross'),

            'approved' => Commission::query()
                ->whereNotNull('reviewed_at')
                ->whereNull('bill_id')
                ->forUser($user)
                ->afterLaunch()
                ->sum('gross'),

            'paid' => Commission::query()
                ->whereIn('bill_id', $bills->pluck('id'))
                ->forUser($user)
                ->sum('gross'),
        ]);
    }
}
