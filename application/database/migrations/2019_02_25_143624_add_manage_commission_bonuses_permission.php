<?php

use App\Models\Permission;
use App\Models\Role;
use App\Policies\CommissionBonusPolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

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
