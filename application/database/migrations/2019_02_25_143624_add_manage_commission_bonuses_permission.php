<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\CommissionBonusPolicy;

class AddManageCommissionBonusesPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Permission::create(['name' => CommissionBonusPolicy::PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }
}
