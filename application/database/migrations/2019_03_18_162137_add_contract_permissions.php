<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class AddContractPermissions extends Migration
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
            $this->createResourcePermission('management.contracts', $roles);
            $this->createResourcePermission('management.contracts.templates', $roles);

            Permission::create(['name' => 'features.contracts.update-special-agreement'])->assignRole($roles);
            Permission::create(['name' => 'features.contracts.update'])->assignRole($roles);

            /** @var Permission $process */
            $process = Permission::findByName('features.users.process');
            $process->update(['name' => 'features.contracts.process']);
        });
    }

    private function createResourcePermission(string $resource, array $roles)
    {
        foreach(['create', 'delete', 'update', 'view'] as $action) {
            Permission::create(['name' => "$resource.$action"])->assignRole($roles);
        }
    }
}
