<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\DB;

trait TestsDatabases
{
    protected function assertTableEmpty(string $table)
    {
        $this->assertEquals(0, DB::table($table)->count());
    }
}
