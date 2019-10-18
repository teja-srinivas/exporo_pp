<?php

declare(strict_types=1);

use App\Models\Role;
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

        $this->createPermission(BillPolicy::DOWNLOAD_PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
