<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommissionBonus;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommissionBonusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CommissionBonus::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CommissionBonus $bonus
     * @return void
     * @throws ValidationException
     */
    public function update(Request $request, CommissionBonus $bonus)
    {
        $this->denyIfLocked($bonus);

        $data = $this->validate($request, CommissionBonus::validationRules());

        $bonus->update($data);
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
        $this->denyIfLocked($bonus);

        $bonus->delete();
    }

    protected function denyIfLocked(CommissionBonus $bonus)
    {
        if ($bonus->contract === null || $bonus->contract->isEditable()) {
            return;
        }

        throw new HttpException(Response::HTTP_LOCKED, 'The contract can no longer be edited');
    }
}
