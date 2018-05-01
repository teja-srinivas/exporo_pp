<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app('cache')->forget('spatie.permission.cache');

        // Create permissions
        Permission::create(['name' => 'create agbs']);
        Permission::create(['name' => 'edit agbs']);
        Permission::create(['name' => 'delete agbs']);

        // Create roles and assign created permissions
        Role::create(['name' => 'user']);

        $role = Role::create(['name' => 'internal']);
        $role->givePermissionTo(['create agbs', 'edit agbs', 'delete agbs']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
