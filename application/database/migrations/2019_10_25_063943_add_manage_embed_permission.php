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

        $this->createResourcePermission(EmbedPolicy::PERMISSION, [
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL),
        ]);
    }
}
