<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\CommissionBonusPolicy;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class AddManageCommissionBonusesPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create(['name' => CommissionBonusPolicy::PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }
}
