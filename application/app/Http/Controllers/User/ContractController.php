<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ContractTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class ContractController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(User $user, Request $request): Response
    {
        $this->authorize('create', Contract::class);

        $data = $this->validate($request, [
            'template' => ['required', Rule::exists('contract_templates', 'id')],
        ]);

        /** @var ContractTemplate $template */
        $template = ContractTemplate::query()->find($data['template']);

        $template->createInstance($user);

        return response()->redirectToRoute('users.show', [$user]);
    }
}
