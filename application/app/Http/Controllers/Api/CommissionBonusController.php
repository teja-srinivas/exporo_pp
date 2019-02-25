<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommissionBonus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommissionBonusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return CommissionBonus
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', CommissionBonus::class);

        $data = $this->validate($request, $this->validationRules());

        $bonus = new CommissionBonus($data);
        $bonus->saveOrFail();

        return $bonus;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\CommissionBonus $bonus
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
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
     * @param  \App\Models\CommissionBonus $bonus
     * @return void
     * @throws \Exception
     */
    public function destroy(CommissionBonus $bonus)
    {
        $this->authorize('delete', $bonus);

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
        ];
    }
}
