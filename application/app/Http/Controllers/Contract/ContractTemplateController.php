<?php

namespace App\Http\Controllers\Contract;

use App\Models\ContractTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

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
        $this->authorize('create', ContractTemplate::class);

        return response()->view('contracts.templates.create');
    }

    /**
     * @param ContractTemplate $template
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(ContractTemplate $template)
    {
        $this->authorize('update', ContractTemplate::class);

        return view('contracts.templates.edit', [
            'template' => $template,
        ]);
    }
}
