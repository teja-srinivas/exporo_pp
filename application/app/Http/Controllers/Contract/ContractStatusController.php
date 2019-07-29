<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $this->authorize('update', $contract);

        $this->checkIfContractIsEditable($contract);

        $data = $this->validate($request, [
            'release' => 'required|boolean',
        ]);

        if ($data['release']) {
            $now = now();
            $contract->forceFill(['released_at' => $now, 'accepted_at' => $now])->save();
            $contract->user->forceFill(['accepted_at' => $now])->save();
            flash_success('Vertrag wurde freigegeben. Der Benutzer ist nun aktiviert.');
        } else {
            $contract->update(['released_at' => null]);
            flash_success('Vertrag wurde wieder in den Entwurfsstatus gestellt.');
        }

        return back();
    }
}
