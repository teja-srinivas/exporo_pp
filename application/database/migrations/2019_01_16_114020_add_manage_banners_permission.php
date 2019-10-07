<?php

use App\Models\Role;
use App\Policies\BannerPolicy;

class AddManageBannersPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(BannerPolicy::PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
