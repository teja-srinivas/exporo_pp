<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Exports\BillsExport;
use Illuminate\Http\Request;

class ExportBillsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request)
    {
        $this->authorize('export', Bill::class);

        $export = new BillsExport($request->get('selection', []));

        return $export->download('Abrechnungen.xlsx');
    }
}
