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
        return response()->view('home', [
            'bills' => Bill::getDetailsPerUser($request->user()->id)->released()->latest()->get(),

            // Stats
            'total' => Commission::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('rejected_at')
                ->sum('net'),

            'approved' => Commission::query()
                ->where(function (Builder $query) {
                    $query->whereNotNull('reviewed_at');
                    $query->orWhereNull('bill_id');
                })
                ->where('user_id', $request->user()->id)
                ->sum('net'),

            'paid' => Commission::query()
                ->join('bills', 'bill_id', 'bills.id')
                ->forUser($request->user())
                ->where('released_at', '<=', now())
                ->sum('net'),
        ]);
    }
}
