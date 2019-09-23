<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (! app()->isLocal()) {
            return;
        }

        DB::transaction(function () {
            $this->call([
                CompanySeeder::class,
                AgbSeeder::class,
                ProjectSeeder::class,
                UserSeeder::class,
            ]);
        });
    }
}
