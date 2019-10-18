<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\CommissionTypePolicy;

class AddManageProvisionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(CommissionTypePolicy::PERMISSION)->assignRole(Role::INTERNAL);
    }
}
