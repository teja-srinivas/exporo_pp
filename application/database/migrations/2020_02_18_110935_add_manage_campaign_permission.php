<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\CampaignPolicy;
use App\Models\Permission;

class AddManageCampaignPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createResourcePermission(CampaignPolicy::PERMISSION, [
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL),
        ]);

        $this->protectFromRole(Role::PARTNER, [
            'management.campaigns.create',
            'management.campaigns.delete',
            'management.campaigns.update',
            'management.campaigns.view',
        ]);
    }

    private function protectFromRole(string $role, array $roles)
    {
        Permission::query()->whereIn('name', $roles)->update([
            'protected' => json_encode([$role]),
        ]);
    }
}
