<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Commission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
        $user = $request->user();

        return response()->view('home', [
            'bills' => Bill::getDetailsPerUser($user->id)->released()->latest()->get(),

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
                ->join('bills', 'bill_id', 'bills.id')
                ->where('released_at', '<=', now())
                ->forUser($user)
                ->sum('gross'),
        ]);
    }
}
