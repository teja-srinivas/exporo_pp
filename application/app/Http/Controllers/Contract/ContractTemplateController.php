<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use Exception;
use Throwable;
use App\Helper\Rules;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommissionBonus;
use Illuminate\Validation\Rule;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ContractTemplateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContractTemplate::class, 'template');
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()->view('contracts.templates.index', [
            'templates' => ContractTemplate::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        return response()->view('contracts.templates.create');
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

        $template = new ContractTemplate(Arr::except($data, 'bonuses'));

        $template->company()->associate($request->user()->company);

        $template->saveOrFail();
        $template->bonuses()->createMany($data['bonuses']);

        return redirect()->route('contracts.templates.index');
    }

    /**
     * @param  ContractTemplate  $template
     * @return Response
     */
    public function edit(ContractTemplate $template)
    {
        return response()->view('contracts.templates.edit', [
            'template' => $template,
        ]);
    }

    /**
     * @param  Request  $request
     * @param  ContractTemplate  $template
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ContractTemplate $template)
    {
        $data = $this->validate($request, [
            'is_default' => ['nullable', 'in:on,1'],
        ] + Rules::prepend($this->validationRules(), 'required'));

        $template->update($data);

        if ((bool) ($data['is_default'] ?? false) === true) {
            $template->makeDefault();
        }

        flash_success();

        return back();
    }

    /**
     * @param  ContractTemplate  $template
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ContractTemplate $template)
    {
        $template->delete();

        return redirect()->route('contracts.templates.index');
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['string'],
            'cancellation_days' => ['numeric', 'min:1', 'max:365'],
            'claim_years' => ['numeric', 'min:1', 'max:7'],
            'vat_amount' => ['numeric'],
            'vat_included' => ['boolean'],
        ];
    }
}
