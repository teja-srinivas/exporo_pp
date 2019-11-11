<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class SetAllowOverheadFlagOnPartnerContractBasedOnProductContractBonuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::query()
            ->with('partnerContract', 'productContract')
            ->whereHas('partnerContract')
            ->whereHas('productContract')
            ->get()
            ->each(static function (User $user) {
                $contract = $user->partnerContract;
                $contract->allow_overhead = $user->productContract->hasOverhead();
                $contract->save();
            });
    }
}
