<?php

use App\Models\Role;
use App\Models\Permission;
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

        Permission::create(['name' => BannerPolicy::PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }
}
