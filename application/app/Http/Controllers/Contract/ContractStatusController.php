<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContractStatusController extends Controller
{
    use ValidatesContracts;

    /**
     * @param Request $request
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $this->authorize('update', $contract);

        $this->checkIfContractIsEditable($contract);
dd($request, $contract);
        $contract->update([
            'released_at' => now(),
        ]);

        flash_success('Vertrag wurde dem Partner zur Unterschrift freigegeben.');

        return back();
    }
}
