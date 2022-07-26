<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;

class AddBillingPermissionToAllRelevantUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Permission $permission */
        $permission = Permission::findByName(\App\Policies\BillPolicy::CAN_BE_BILLED_PERMISSION);

        // Give the permission to all current partners
        $permission->users()->attach(
            Role::findByName(Role::PARTNER)->users()->pluck('id')
        );
    }
}
