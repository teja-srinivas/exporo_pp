<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\BillPolicy;

class AddDownloadBillsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Permission::create(['name' => BillPolicy::DOWNLOAD_PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }
}
