<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\CommissionBonusPolicy;

class AddManageCommissionBonusesPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(CommissionBonusPolicy::PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
