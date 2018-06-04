<?php

use App\Permission;
use App\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class RemoveModelBasedPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Delete our old ones (again)
        Permission::query()->delete();

        // Create permissions
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->delete();
    }
}
