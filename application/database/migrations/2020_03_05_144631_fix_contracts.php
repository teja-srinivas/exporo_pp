<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contracts')
            ->where('released_at', '>', '2020-03-11 09:00:00')
            ->delete();

        DB::table('commission_bonuses')
            ->where('created_at', '>', '2020-03-11 09:00:00')
            ->where('contract_id', '!=', 44319)
            ->delete();

        DB::table('contracts')
            ->where('terminated_at', '>', '2020-03-11 09:00:00')
            ->update(['terminated_at' => null]);
    }
}
