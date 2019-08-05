<?php

namespace App\Http\Controllers\Contract;

use App\Models\Contract;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidatesContracts
{
    public function checkIfContractIsEditable(Contract $contract)
    {
        if ($contract->isEditable()) {
            return;
        }

        throw new HttpResponseException(
            redirect()->route('contracts.show', $contract)->with([
                'error-message' => 'Dieser Vertrag kann nicht mehr bearbeitet werden.',
            ])
        );
    }
}
