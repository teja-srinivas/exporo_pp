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
            ->where('created_at', '2020-03-09 13:39:02')
            ->delete();

        DB::table('commission_bonuses')
            ->where('created_at', '2020-03-09 13:39:02')
            ->delete();
    }
}
