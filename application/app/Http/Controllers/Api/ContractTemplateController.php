<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Rules\ModelExists;
use Illuminate\Http\Request;
use App\Models\CommissionType;
use App\Models\CommissionBonus;
use Illuminate\Validation\Rule;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class ContractTemplateController extends Controller
{
    /**
     * @param ContractTemplate $template
     * @param Request $request
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(ContractTemplate $template, Request $request)
    {
        $this->authorize('update', $template);

        $data = $this->validate($request, [
            'calculation_type' => [Rule::in(CommissionBonus::TYPES)],
            'is_overhead' => ['boolean'],
            'is_percentage' => ['boolean'],
            'type_id' => [new ModelExists(new CommissionType())],
            'value' => ['numeric'],
        ]);

        $template->update($data);
    }
}
