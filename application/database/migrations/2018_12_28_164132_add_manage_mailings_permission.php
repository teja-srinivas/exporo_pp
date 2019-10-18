<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\MailingPolicy;

class AddManageMailingsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(MailingPolicy::PERMISSION)->assignRole(
            Role::ADMIN,
            Role::INTERNAL
        );
    }
}
