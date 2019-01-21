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
            'total' => Commission::query()
                ->forUser($user)
                ->whereNull('rejected_at')
                ->sum('gross'),

            'approved' => Commission::query()
                ->where(function (Builder $query) {
                    $query->whereNotNull('reviewed_at');
                })
                ->forUser($user)
                ->sum('gross'),

            'paid' => Commission::query()
                ->join('bills', 'bill_id', 'bills.id')
                ->forUser($user)
                ->where('released_at', '<=', now())
                ->sum('gross'),
        ]);
    }
}
