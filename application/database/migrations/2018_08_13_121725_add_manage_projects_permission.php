<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\ProjectPolicy;

class AddManageProjectsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(ProjectPolicy::PERMISSION)->assignRole(Role::INTERNAL);
    }
}
