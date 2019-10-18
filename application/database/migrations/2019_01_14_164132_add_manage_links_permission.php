<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\LinkPolicy;

class AddManageLinksPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(LinkPolicy::PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
