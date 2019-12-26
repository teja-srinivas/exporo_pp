<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Investment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $investments = $user->investments()
            ->where('investments.created_at', '>=', Carbon::now()->startOfMonth()->subMonths(12))
            ->where('investments.created_at', '<', Carbon::now()->startOfMonth())
            ->whereNull('investments.cancelled_at')
            ->get()
            ->map(static function (Investment $investment) {
                return [
                    'amount' => $investment->amount,
                    'created_at' => $investment->created_at,
                    'investment_type' => $investment->is_first_investment ? 'first' : 'subsequent',
                    ];
            })->all();

        return response()->view('dashboard.index', [
            'investments' => $investments,
        ]);
    }
}
