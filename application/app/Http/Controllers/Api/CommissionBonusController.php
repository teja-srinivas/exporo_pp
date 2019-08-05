<?php

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use App\Models\BonusBundle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommissionBonus;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommissionBonusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return CommissionBonus
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', CommissionBonus::class);

        $data = $this->validate($request, $this->validationRules());
        $bundle = $this->validateBundle($data['bundle_id'] ?? null);

        $bonus = new CommissionBonus($data);
        $this->denyIfLocked($bonus);

        $bonus->saveOrFail();
        $bonus->bundle()->attach($bundle);

        return $bonus;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CommissionBonus $bonus
     * @return void
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Request $request, CommissionBonus $bonus)
    {
        $this->authorize('update', $bonus);

        $this->denyIfLocked($bonus);

        $data = $this->validate($request, $this->validationRules());

        $bonus->fill($data)->saveOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CommissionBonus $bonus
     * @return void
     * @throws Exception
     */
    public function destroy(CommissionBonus $bonus)
    {
        $this->authorize('delete', $bonus);

        $this->denyIfLocked($bonus);

        $bonus->bundle()->detach();

        $bonus->delete();
    }

    /**
     * @return array
     */
    private function validationRules(): array
    {
        return [
            'type_id' => ['required', Rule::exists('commission_types', 'id')],
            'contract_id' => ['required'],
            'calculation_type' => ['required', Rule::in(CommissionBonus::TYPES)],
            'value' => ['required', 'numeric'],
            'is_percentage' => ['required', 'boolean'],
            'is_overhead' => ['required', 'boolean'],
            'bundle_id' => ['sometimes', Rule::exists('bundles', 'id')],
        ];
    }

    /**
     * @param string|int $bundleId
     * @return BonusBundle|null
     * @throws AuthorizationException
     */
    protected function validateBundle($bundleId): ?BonusBundle
    {
        if ($bundleId === null) {
            return null;
        }

        /** @var BonusBundle $bundle */
        $bundle = BonusBundle::query()->findOrFail($bundleId);

        $this->authorize('update', $bundle);

        return $bundle;
    }

    protected function denyIfLocked(CommissionBonus $bonus)
    {
        if ($bonus->contract === null || $bonus->contract->isEditable()) {
            return;
        }

        throw new HttpException(Response::HTTP_LOCKED, 'The contract can no longer be edited');
    }
}
