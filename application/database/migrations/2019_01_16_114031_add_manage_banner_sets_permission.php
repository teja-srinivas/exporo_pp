<?php

use App\Models\Role;
use App\Policies\BannerSetPolicy;

class AddManageBannerSetsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(BannerSetPolicy::PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
