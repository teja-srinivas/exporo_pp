<?php

use App\Models\Role;
use App\Models\Permission;

class AddInitialRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create(['name' => Role::PARTNER]);
        Role::create(['name' => Role::INTERNAL]);
        Role::create(['name' => Role::ADMIN]);

        Permission::create(['name' => 'manage agbs']);
        Permission::create(['name' => 'manage documents']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage authorization']);
        Permission::create(['name' => 'process partners']);

        Role::findByName(Role::ADMIN)->givePermissionTo(
            Permission::create(['name' => 'view audits'])
        );

        Role::findByName(Role::PARTNER)->givePermissionTo(
            Permission::create(['name' => 'view partner dashboard'])
        );
    }
}
