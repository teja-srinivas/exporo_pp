<?php

use App\Models\Role;
use App\Models\Permission;
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

    private function createResourcePermission(string $resource, array $roles)
    {
        foreach (['create', 'delete', 'update', 'view'] as $action) {
            Permission::create(['name' => "$resource.$action"])->assignRole($roles);
        }
    }
}
