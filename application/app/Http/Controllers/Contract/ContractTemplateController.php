<?php

namespace App\Http\Controllers\Contract;

use App\Helper\Rules;
use Exception;
use Illuminate\Support\Arr;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommissionBonus;
use Illuminate\Validation\Rule;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class ContractTemplateController extends Controller
{
    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', ContractTemplate::class);

        return response()->view('contracts.templates.index', [
            'templates' => ContractTemplate::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorizeResource(ContractTemplate::class);

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
        $this->authorizeResource(ContractTemplate::class);

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
        $this->authorizeResource($template);

        return view('contracts.templates.edit', [
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
        $this->authorizeResource($template);

        $template->update(
            $this->validate($request, Rules::prefix('required', $this->validationRules()))
        );

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
        $this->authorizeResource($template);

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