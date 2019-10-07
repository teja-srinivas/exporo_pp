<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;

class AddComissionPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $names = $this->createResourcePermission(
            'management.commissions',
            [Role::ADMIN, Role::INTERNAL]
        )->pluck('name');

        Permission::query()->whereIn('name', $names)->update([
            'protected' => json_encode([Role::PARTNER]),
        ]);
    }
}
