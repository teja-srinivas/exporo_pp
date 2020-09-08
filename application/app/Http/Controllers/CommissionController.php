<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Console\Commands\CalculateCommissions;
use App\Models\Commission;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Commission::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $totals = $this->calculateTotals();

        return response()->view('commissions.approval', [
            'totals' => [
                'count' => (int) ($totals->count ?:0),
                'gross' => (float) ($totals->gross ?:0),
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function pending(): Response
    {
        $totals = $this->calculateTotals(true);

        return response()->view('commissions.pending', [
            'totals' => [
                'count' => (int) ($totals->count ?:0),
                'gross' => (float) ($totals->gross ?:0),
            ],
        ]);
    }

    private function calculateTotals(bool $pending = false): Commission
    {
        // Refresh commissions before entering page as we might
        // have had updates on some other models that caused
        // some precalculated commissions to be invalidated
        if (request()->user()->can('create', Commission::class)) {
            Artisan::call(CalculateCommissions::class);
        }

        return $pending
            ? Commission::query()
                ->where('pending', true)
                ->selectRaw('SUM(gross) as gross')
                ->selectRaw('COUNT(commissions.id) as count')
                ->isOpen()
                ->afterLaunch()
                ->get()
                ->first()
            :Commission::query()
                ->selectRaw('SUM(gross) as gross')
                ->selectRaw('COUNT(commissions.id) as count')
                ->isOpen()
                ->isAcceptable()
                ->afterLaunch()
                ->get()
                ->first();
    }
}
