<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\CommissionBonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class DuplicateContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(static function () {
            User::query()
                ->get()
                ->each(static function (User $user) {
                    if ($user->partnerContract !== null) {
                        $new = $user->partnerContract->replicate();
                        $new->save();
                        $new->accepted_at = null;
                        $new->signature = null;
                        $new->released_at = now();
                        $new->terminated_at = null;
                        $new->save();
                    }

                    if ($user->productContract === null) {
                        return;
                    }

                    $old = $user->productContract;
                    $new = $old->replicate();
                    $old->terminated_at = now();
                    $old->save();
                    $new->save();
                    $new->accepted_at = now();
                    $new->released_at = now();
                    $new->terminated_at = null;
                    $new->save();
                    $user->productContract->bonuses->each(
                        static function (CommissionBonus $bonus) use ($new) {
                            $newBonus = $bonus->replicate();
                            $newBonus->save();
                            $newBonus->contract_id = $new->id;
                            $newBonus->save();
                        }
                    );
                });
        });
    }
}
