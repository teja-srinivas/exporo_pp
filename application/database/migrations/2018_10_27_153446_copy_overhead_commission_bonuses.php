<?php

use App\Models\User;
use App\Models\CommissionBonus;

class CopyOverheadCommissionBonuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Only copy the values from parent users since
        // they're the only ones we actually calculate
        // the overhead commissions for anyway.
        $usersWithParents = User::query()
            ->select('parent_id')
            ->where('parent_id', '>', 0)
            ->distinct();

        $childUserBonuses = CommissionBonus::query()
            ->whereIn('user_id', $usersWithParents)
            ->whereIn('calculation_type', [
                CommissionBonus::TYPE_FIRST_INVESTMENT,
                CommissionBonus::TYPE_FURTHER_INVESTMENT,
            ])
            ->get();

        $now = now()->toDateTimeString();

        $inserts = $childUserBonuses->map(function (CommissionBonus $bonus) use ($now) {
            return [
                'type_id' => $bonus->type_id,
                'user_id' => $bonus->user_id,
                'calculation_type' => $bonus->calculation_type,
                'value' => $bonus->value,
                'is_percentage' => $bonus->is_percentage,
                'accepted_at' => $bonus->accepted_at,
                'created_at' => $now,
                'updated_at' => $now,

                'is_overhead' => true,
            ];
        });

        CommissionBonus::query()->insert($inserts->all());
    }
}
