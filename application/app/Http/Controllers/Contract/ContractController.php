<?php

namespace App\Http\Controllers\Contract;

use App\Helper\Rules;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class ContractController extends Controller
{
    use ValidatesContracts;

    /**
     * Display the specified resource.
     *
     * @param Contract $contract
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);

        return view('contracts.show', [
            'contract' => $contract,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contract $contract
     * @return Response|RedirectResponse
     * @throws AuthorizationException
     */
    public function edit(Contract $contract)
    {
        $this->authorize('update', $contract);

        $this->checkIfContractIsEditable($contract);

        return view('contracts.edit', [
            'contract' => $contract,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Contract $contract
     * @return Response|RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);

        $this->checkIfContractIsEditable($contract);

        $data = $this->validate($request, Rules::byPermission([
            'features.contracts.update-cancellation-period' => [
                'cancellation_days' => ['required', 'numeric', 'min:1', 'max:365'],
            ],
            'features.contracts.update-claim' => [
                'claim_years' => ['required', 'numeric', 'min:1', 'max:7'],
            ],
            'features.contracts.update-special-agreement' => [
                'special_agreement' => ['nullable'],
            ],
            'features.contracts.update-vat-details' => [
                'vat_amount' => ['numeric'],
                'vat_included' => ['boolean'],
            ],
        ]));

        $contract->update($data);

        flash_success();

        return back();
    }
}
