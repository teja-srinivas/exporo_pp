<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\BillPolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class CreateCanBeBilledPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::create(['name' => Role::LOCKED])->givePermissionTo(
            'view partner dashboard'
        );

        Permission::create(['name' => BillPolicy::CAN_BE_BILLED_PERMISSION])->assignRole(
            Role::findByName(Role::PARTNER)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::query()->where('name', Role::LOCKED)->delete();
        Permission::query()->where('name', BillPolicy::CAN_BE_BILLED_PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
