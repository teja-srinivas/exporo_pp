<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CommissionBonus;

class CommissionBonusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @param  \App\Models\CommissionBonus $commissionBonus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user, CommissionBonus $commissionBonus)
    {
        $commissionBonus->value = $request->get('value');
        $commissionBonus->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @param  \App\Models\CommissionBonus $commissionBonus
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user, CommissionBonus $commissionBonus)
    {
        $commissionBonus->delete();

        return back();
    }
}
