<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class FixTerminationDatesOnContracts extends Migration
{
    /**
     * Sets the terminated_at date for contracts in the past
     * based on the accepted_at date from future, active contracts.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(static function () {
            $types = DB::table('contracts')->distinct()->pluck('type');
            $userIds = DB::table('contracts')->distinct()->pluck('user_id');

            $userIds->each(static function ($userId) use ($types) {
                $types->each(static function (string $type) use ($userId) {
                    // Previous is the one from the future, since we go backwards in time
                    $previous = null;

                    DB::table('contracts')
                        ->where('type', $type)
                        ->where('user_id', $userId)
                        ->latest('accepted_at')
                        ->each(static function (stdClass $contract) use (&$previous) {
                            if (
                                $previous !== null
                                && $contract->released_at !== null
                                && $contract->accepted_at !== null
                                && $contract->terminated_at === null
                            ) {
                                DB::table('contracts')
                                    ->where('id', $contract->id)
                                    ->update(['terminated_at' => $previous->accepted_at]);
                            }

                            $previous = $contract;
                        });
                });
            });
        });
    }
}
