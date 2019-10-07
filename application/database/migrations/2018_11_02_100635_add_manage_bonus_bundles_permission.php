<?php

use App\Models\Role;
use App\Models\Permission;

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

        Role::findByName(Role::INTERNAL)->givePermissionTo(
            Permission::create(['name' => 'management.commission-bonus-bundles'])
        );
    }
}
