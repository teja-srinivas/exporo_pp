<?php

declare(strict_types=1);

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AddInvestorPermissions extends Migration
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
        ];

        // Let's better be safe than sorry
        DB::transaction(function () use ($roles) {
            $this->createResourcePermission('management.investments', $roles);
            $this->createResourcePermission('management.investors', $roles);
        });
    }
}
