<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Commission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                ->where('commissions.user_id', $request->user()->id)
                ->where('released_at', '<=', now())
                ->sum('net'),
        ]);
    }
}
