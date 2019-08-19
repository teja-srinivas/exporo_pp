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
    }

    /**
     * @param  ContractTemplate  $template
     * @param  Request  $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(ContractTemplate $template, Request $request): JsonResource
    {
        $this->authorizeResource(CommissionBonus::class);

        $data = $this->validate($request, Rules::prefix('required', CommissionBonus::validationRules()));

        return Resource::make($template->bonuses()->create($data));
    }
}
