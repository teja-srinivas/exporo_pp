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
            ->whereIn('id', [
                48151,
                48155,
                48159,
                48163,
                48167,
                48171,
                48174,
                48179,
                48183,
                48187,
                48191,
                48195,
                48199,
                48202,
                48207,
                48210,
                48215,
                48219,
                48223,
                48227,
                48231,
                48235,
                48239,
                48243,
                48247,
                48251,
                48255,
                48258,
                48263,
                48267,
                48271,
                48275,
                48279,
                48283,
            ])
            ->update(['terminated_at' => '2020-03-13 11:11:11']);
    }
}
