<?php

declare(strict_types=1);

use App\Models\Role;

class AddAffiliateDashboardPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('management.affiliate.dashboard.view')->assignRole([
            Role::ADMIN,
            Role::INTERNAL,
        ]);
    }
}
