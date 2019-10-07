<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CalculateCommissions;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Commission::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Refresh commissions before entering page as we might
        // have had updates on some other models that caused
        // some precalculated commissions to be invalidated
        Artisan::call(CalculateCommissions::class);

        $totals = Commission::query()
            ->selectRaw('SUM(gross) as gross')
            ->selectRaw('COUNT(commissions.id) as count')
            ->isOpen()
            ->isAcceptable()
            ->afterLaunch()
            ->get()
            ->first();

        return response()->view('commissions.approval', [
            'totals' => [
                'count' => (int) ($totals->count ?: 0),
                'gross' => (float) ($totals->gross ?: 0),
            ],
        ]);
    }
}
