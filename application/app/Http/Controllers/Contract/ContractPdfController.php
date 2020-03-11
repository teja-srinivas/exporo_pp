<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use App\Models\Contract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class ContractPdfController extends Controller
{
    public function show(Contract $contract): Response
    {
        $bonuses = $contract->bonuses->sortBy(static function ($bonus) {
            return $bonus->type_id;
        });
        $view = "contracts.pdf.{$contract->type}";

        abort_unless(View::exists($view), Response::HTTP_NOT_FOUND);

        return response()->view($view, [
            'contract' => $contract,
            'bonuses' => $bonuses,
        ]);
    }
}
