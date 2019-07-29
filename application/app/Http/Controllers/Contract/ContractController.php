<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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

        $data = $this->validate($request, [
            'cancellation_days' => ['required', 'numeric', 'min:1', 'max:365'],
            'claim_years' => ['required', 'numeric', 'min:1', 'max:10'],
            'special_agreement' => ['nullable'],
            'vat_amount' => ['numeric'],
            'vat_included' => ['boolean'],
        ]);

        $contract->update($data);

        flash_success();

        return back();
    }
}
