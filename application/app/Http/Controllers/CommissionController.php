<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totals = Commission::query()
            ->selectRaw('SUM(gross) as gross')
            ->selectRaw('SUM(net) as net')
            ->selectRaw('COUNT(commissions.id) as count')
            ->isOpen()
            ->isAcceptable()
            ->get()
            ->first();

        return response()->view('commissions.approval', [
            'totals' => [
                'count' => (int)($totals->count ?: 0),
                'gross' => (float)($totals->gross ?: 0),
                'net' => (float)($totals->net ?: 0),
            ],
        ]);
    }
}
