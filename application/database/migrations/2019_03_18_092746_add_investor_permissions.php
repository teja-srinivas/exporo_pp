<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class AddInvestorPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

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
        foreach(['create', 'delete', 'update', 'view'] as $action) {
            Permission::create(['name' => "$resource.$action"])->assignRole($roles);
        }
    }
}
