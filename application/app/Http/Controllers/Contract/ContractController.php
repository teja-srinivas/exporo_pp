<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use App\Helper\Rules;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{
    use ValidatesContracts;

    public function __construct()
    {
        $this->authorizeResource(Contract::class);
    }

    /**
     * Display the specified resource.
     *
     * @param Contract $contract
     * @return Response
     */
    public function show(Contract $contract): Response
    {
        return response()->view('contracts.show', [
            'contract' => $contract,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contract $contract
     * @return Response
     */
    public function edit(Contract $contract): Response
    {
        $this->checkIfContractIsEditable($contract);

        return response()->view('contracts.edit', [
            'contract' => $contract,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Contract $contract
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $this->checkIfContractIsEditable($contract);

        $data = $this->validate($request, Rules::byPermission([
            'management.contracts.update-cancellation-period' => [
                'cancellation_days' => ['required', 'numeric', 'min:1', 'max:365'],
            ],
            'management.contracts.update-claim' => [
                'claim_years' => ['required', 'numeric', 'min:1', 'max:7'],
            ],
            'management.contracts.update-special-agreement' => [
                'special_agreement' => ['nullable'],
            ],
            'management.contracts.update-vat-details' => [
                'vat_amount' => ['numeric'],
                'vat_included' => ['boolean'],
            ],
        ]));

        $contract->update($data);

        flash_success();

        return response()->redirectToRoute('contracts.edit', [$contract]);
    }

    /**
     * @param  Contract  $contract
     * @return RedirectResponse
     */
    public function destroy(Contract $contract): RedirectResponse
    {
        $this->checkIfContractIsEditable($contract);

        DB::transaction(static function () use ($contract) {
            $contract->delete();
        });

        flash_success('Vertragsentwurf wurde gelöscht');

        return response()->redirectToRoute('users.show', $contract->user);
    }
}
