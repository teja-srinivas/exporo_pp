<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Models\CommissionBonus;
use Illuminate\Validation\Rule;
use App\Models\ContractTemplate;
use Illuminate\Support\Facades\DB;
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
        $contract = Contract::fromTemplate($template);

        DB::transaction(static function () use ($contract, $user, $template) {
            $contract->user()->associate($user);
            $contract->save();

            $contract->bonuses()->createMany($template->bonuses->map(static function (CommissionBonus $template) {
                return $template->replicate()->attributesToArray();
            })->all());
        });

        if ($user->can('update', $contract)) {
            return response()->redirectToRoute('contracts.edit', $contract);
        }

        return response()->redirectToRoute('contracts.show', $contract);
    }
}
