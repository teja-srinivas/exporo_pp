<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Policies\BillPolicy;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class CreateCanBeBilledPermission extends Migration
{
    const NAME = 'gesperrt';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::create(['name' => self::NAME])->givePermissionTo(
            'view partner dashboard'
        );

        Permission::create(['name' => BillPolicy::CAN_BE_BILLED_PERMISSION])->assignRole(
            Role::findByName(Role::PARTNER)
        );
    }
}
