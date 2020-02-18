<?php

declare(strict_types=1);

use App\Models\Role;
use App\Policies\CampaignPolicy;

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
    }
}
