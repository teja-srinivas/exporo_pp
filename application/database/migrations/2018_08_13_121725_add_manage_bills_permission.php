<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\BillPolicy;

class AddManageBillsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(BillPolicy::PERMISSION)->assignRole(Role::INTERNAL);
    }
}
