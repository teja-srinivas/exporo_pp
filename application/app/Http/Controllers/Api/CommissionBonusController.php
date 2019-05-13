<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BonusBundle;
use App\Models\CommissionBonus;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        $bundle = $this->validateBundle($data['bundle_id'] ?? null, 'update');

        $bonus = new CommissionBonus($data);
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
            'user_id' => ['required'],
            'calculation_type' => ['required', Rule::in(CommissionBonus::TYPES)],
            'value' => ['required', 'numeric'],
            'is_percentage' => ['required', 'boolean'],
            'is_overhead' => ['required', 'boolean'],
            'bundle_id' => ['sometimes', Rule::exists('bundles', 'id')],
        ];
    }

    /**
     * @param string|int $bundleId
     * @param string $ability
     * @return BonusBundle|null
     * @throws AuthorizationException
     */
    protected function validateBundle($bundleId, string $ability): ?BonusBundle
    {
        if ($bundleId === null) {
            return null;
        }

        /** @var BonusBundle $bundle */
        $bundle = BonusBundle::query()->findOrFail($bundleId);

        $this->authorize($ability, $bundle);

        return $bundle;
    }
}
