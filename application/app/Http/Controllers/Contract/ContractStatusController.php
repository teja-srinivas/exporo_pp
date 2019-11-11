<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class ContractStatusController extends Controller
{
    use ValidatesContracts;

    /**
     * @param Request $request
     * @param Contract $contract
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $this->authorize('process', $contract);

        $this->checkIfContractIsEditable($contract);

        $data = $this->validate($request, [
            'release' => 'required|boolean',
        ]);

        if ($data['release']) {
            $contract->released_at = now();
            $contract->save();
            flash_success('Vertrag wurde freigegeben.');
        } else {
            $contract->update(['released_at' => null]);
            flash_success('Vertrag wurde wieder in den Entwurfsstatus gestellt.');
        }

        return response()->redirectToRoute('users.show', [$contract->user]);
    }
}
