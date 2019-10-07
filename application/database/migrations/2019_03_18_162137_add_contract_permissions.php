<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class AddContractPermissions extends Migration
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
            $this->createResourcePermission('management.contracts', $roles);
            $this->createResourcePermission('management.contracts.templates', $roles);

            $this->createPermission('features.contracts.update-special-agreement')->assignRole($roles);
            $this->createPermission('features.contracts.update')->assignRole($roles);

            /** @var Permission $process */
            $process = Permission::findByName('features.users.process');
            $process->update(['name' => 'features.contracts.process']);
        });
    }
}
