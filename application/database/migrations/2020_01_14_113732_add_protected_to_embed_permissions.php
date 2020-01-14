<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;

class AddProtectedToEmbedPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->protectFromRole(Role::PARTNER, [
            'management.affiliate.embeds.create',
            'management.affiliate.embeds.delete',
            'management.affiliate.embeds.update',
        ]);
    }

    private function protectFromRole(string $role, array $roles)
    {
        Permission::query()->whereIn('name', $roles)->update([
            'protected' => json_encode([$role]),
        ]);
    }
}
