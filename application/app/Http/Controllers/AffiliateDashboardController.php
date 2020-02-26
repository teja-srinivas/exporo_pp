<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class AffiliateDashboardController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $this->authorize('management.affiliate.dashboard.view');

        return view('affiliate.dashboard.index');
    }
}
