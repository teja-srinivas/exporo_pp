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
            ->update(['pdf_generated_at' => null]);
    }
}
