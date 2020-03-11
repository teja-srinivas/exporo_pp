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
        $time = now();

        User::query()
            ->get()
            ->each(static function (User $user) use ($time) {
                DB::transaction(static function () use ($time, $user) {
                    $check = DB::table('contracts')
                        ->where('created_at', '>', '2020-03-11 07:01:03')
                        ->where('user_id', $user->id)
                        ->exists();

                    if ($check) {
                        return;
                    }

                    if ($user->partnerContract !== null) {
                        $new = $user->partnerContract->replicate();
                        $new->save();
                        $new->accepted_at = null;
                        $new->signature = null;
                        $new->released_at = $time;
                        $new->terminated_at = null;
                        $new->pdf_generated_at = null;
                        $new->save();
                    }

                    if ($user->productContract === null) {
                        return;
                    }

                    $old = $user->productContract;
                    $new = $old->replicate();
                    $old->terminated_at = $time;
                    $old->save();
                    $new->save();
                    $new->accepted_at = $time;
                    $new->released_at = $time;
                    $new->terminated_at = null;
                    $new->pdf_generated_at = null;
                    $new->save();
                    $user->productContract->bonuses->each(
                        static function (CommissionBonus $bonus) use ($new, $time) {
                            $newBonus = $bonus->replicate();
                            $newBonus->save();
                            $newBonus->contract_id = $new->id;
                            $newBonus->created_at = $time;
                            $newBonus->updated_at = $time;
                            $newBonus->save();
                        }
                    );
                });
            });
    }
}
