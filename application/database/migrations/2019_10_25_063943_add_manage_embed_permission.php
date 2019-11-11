<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\EmbedPolicy;

class AddManageEmbedPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $roles = [
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL),
            Role::findByName(Role::PARTNER),
        ];

        $this->createResourcePermission(EmbedPolicy::PERMISSION, $roles);
    }
}
