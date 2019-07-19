<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Contract $contract
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Contract $contract)
    {
        $this->authorize('update', $contract);

        return view('contracts.edit', [
            'contract' => $contract,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Contract $contract
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);

        $data = $this->validate($request, [
            'cancellation_days' => ['required', 'numeric', 'min:14', 'max:365'],
            'claim_years' => ['required', 'numeric', 'min:1', 'max:10'],
            'special_agreement' => ['nullable'],
        ]);

        $contract->update($data);

        flash_success();

        return back();
    }
}
