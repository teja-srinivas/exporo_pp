<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\MailingPolicy;

class AddManageMailingsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Permission::create(['name' => MailingPolicy::PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }
}
