<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class CreateStandardRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reset cached roles and permissions
        app('cache')->forget('spatie.permission.cache');

        // Create permissions
        Permission::create(['name' => 'create agbs']);
        Permission::create(['name' => 'edit agbs']);
        Permission::create(['name' => 'delete agbs']);

        // Create roles and assign created permissions
        Role::create(['name' => Role::PARTNER]);

        $role = Role::create(['name' => Role::INTERNAL]);
        $role->givePermissionTo(['create agbs', 'edit agbs', 'delete agbs']);

        // Admins have the permission to do anything by design
        Role::create(['name' => Role::ADMIN]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->delete();
        Role::query()->delete();
    }
}
