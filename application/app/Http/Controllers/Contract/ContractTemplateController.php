<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contract;

use Exception;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
            'templateTypes' => collect(Contract::TYPES)->mapWithKeys(static function (string $name) {
                return [$name => __("contracts.{$name}.title")];
            }),
        ]);
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'type' => ['required', Rule::in(Contract::TYPES)],
        ]);

        return response()->redirectToRoute("contracts.templates.{$data['type']}.create");
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
     * @param  ContractTemplate  $template
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ContractTemplate $template)
    {
        $template->delete();

        return redirect()->route('contracts.templates.index');
    }
}
