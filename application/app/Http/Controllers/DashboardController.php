<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        if (!$request->user()->can('management.dashboard.view')) {
            return redirect()->route('accounting');
        }

        return response()->view('dashboard.index');
    }
}
