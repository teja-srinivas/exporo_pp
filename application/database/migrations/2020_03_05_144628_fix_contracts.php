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
            ->where('created_at', '2020-03-05 16:37:16')
            ->delete();

        DB::table('contracts')
            ->where('created_at', '2020-03-05 16:37:17')
            ->delete();

        DB::table('commission_bonuses')
            ->where('created_at', '2020-03-05 16:37:16')
            ->delete();

        DB::table('commission_bonuses')
            ->where('created_at', '2020-03-05 16:37:17')
            ->delete();
    }
}
