<?php

declare(strict_types=1);

use App\Models\Role;

class AddDashboardPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('management.dashboard.view')->assignRole([
            Role::ADMIN,
            Role::INTERNAL,
        ]);
    }
}
