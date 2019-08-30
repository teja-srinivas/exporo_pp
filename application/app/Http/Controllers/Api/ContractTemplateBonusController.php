<?php

namespace App\Http\Controllers\Api;

use App\Helper\Rules;
use Illuminate\Http\Request;
use App\Models\CommissionBonus;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\CommissionBonus as Resource;

class ContractTemplateBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update,template');
        $this->authorizeResource(CommissionBonus::class);
    }

    /**
     * @param  ContractTemplate  $template
     * @param  Request  $request
     * @return JsonResource
     * @throws ValidationException
     */
    public function store(ContractTemplate $template, Request $request): JsonResource
    {
        $rules = Rules::prepend(CommissionBonus::validationRules(), 'required');
        $data = $this->validate($request, $rules);

        return Resource::make($template->bonuses()->create($data));
    }
}
