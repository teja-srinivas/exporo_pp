<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use Throwable;
use App\Helper\Rules;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CommissionBonus;
use App\Models\ProductContract;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\ProductContractTemplate;
use Illuminate\Validation\ValidationException;

class ProductContractTemplateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContractTemplate::class, 'template');
    }

    /**
     * @return Response
     */
    public function create()
    {
        return response()->view('contracts.templates.create', [
            'type' => ProductContract::STI_TYPE,
        ]);
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, $this->validationRules() + [
            'bonuses.*.type_id' => Rule::exists('commission_types', 'id'),
            'bonuses.*.value' => 'numeric',
            'bonuses.*.calculation_type' => Rule::in(CommissionBonus::TYPES),
            'bonuses.*.is_percentage' => 'boolean',
            'bonuses.*.is_overhead' => 'boolean',
        ]);

        $data['is_default'] = (bool) ($data['is_default'] ?? false);

        $template = new ProductContractTemplate(Arr::except($data, 'bonuses'));

        $template->company()->associate($request->user()->company);

        $template->saveOrFail();
        $template->bonuses()->createMany($data['bonuses'] ?? []);

        return redirect()->route('contracts.templates.index');
    }

    /**
     * @param  Request  $request
     * @param  ProductContractTemplate  $template
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ProductContractTemplate $template)
    {
        $data = $this->validate($request, [
            'is_default' => ['nullable', 'in:on,1'],
        ] + Rules::prepend($this->validationRules(), 'required'));

        $data['is_default'] = (bool) ($data['is_default'] ?? false);

        $template->update($data);

        flash_success();

        return back();
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['string'],
            'vat_amount' => ['numeric'],
            'vat_included' => ['boolean'],
        ];
    }
}
