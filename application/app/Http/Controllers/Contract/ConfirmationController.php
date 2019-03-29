<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;

class ConfirmationController extends Controller
{
    public function show(Contract $contract)
    {
        return view('contracts.confirm', [
            'contract' => $contract,
        ]);
    }
}
