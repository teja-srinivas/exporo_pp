<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helper\Rules;
use Illuminate\Http\Request;
use App\Models\CommissionBonus;
use App\Models\ProductContract;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommissionBonus as Resource;

class ProductContractBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update,contract');
        $this->authorizeResource(CommissionBonus::class);
    }

    /**
     * @param  ProductContract  $contract
     * @param  Request  $request
     * @return JsonResource
     * @throws ValidationException
     */
    public function store(ProductContract $contract, Request $request): JsonResource
    {
        $rules = Rules::prepend(CommissionBonus::validationRules(), 'required');
        $data = $this->validate($request, $rules);

        return Resource::make($contract->bonuses()->create($data));
    }
}
