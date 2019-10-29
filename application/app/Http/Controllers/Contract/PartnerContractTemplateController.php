<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use Throwable;
use App\Helper\Rules;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PartnerContract;
use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\PartnerContractTemplate;
use Illuminate\Validation\ValidationException;

class PartnerContractTemplateController extends Controller
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
            'type' => PartnerContract::STI_TYPE,
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
        $data = $this->validate($request, Rules::prepend($this->validationRules(), 'required'));
        $data['is_default'] = (bool) ($data['is_default'] ?? false);

        $template = new PartnerContractTemplate($data);
        $template->company()->associate($request->user()->company);
        $template->saveOrFail();

        return redirect()->route('contracts.templates.index');
    }

    /**
     * @param  Request  $request
     * @param  PartnerContractTemplate  $template
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, PartnerContractTemplate $template)
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
            'cancellation_days' => ['numeric', 'min:1', 'max:365'],
            'claim_years' => ['numeric', 'min:1', 'max:7'],
        ];
    }
}
