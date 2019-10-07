<?php

use App\Models\Role;

class AddManageBonusBundlesPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('management.commission-bonus-bundles')->assignRole(Role::INTERNAL);
    }
}
