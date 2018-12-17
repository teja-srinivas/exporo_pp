<?php

namespace App\Http\Controllers;

use App\Exports\BillsExport;
use App\Policies\BillPolicy;
use Illuminate\Http\Request;

class ExportBillsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function __invoke(Request $request)
    {
        abort_unless($request->user()->can(BillPolicy::PERMISSION), 403);

        return (new BillsExport)->download('Abrechnungen.xlsx');
    }
}
