<?php

declare(strict_types=1);

use App\Models\Role;

class AddUserLoginPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission('features.users.login')->update([
            'protected' => json_encode([Role::PARTNER]),
        ]);
    }
}
