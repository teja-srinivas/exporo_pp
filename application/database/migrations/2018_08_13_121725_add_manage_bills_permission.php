<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class AddManageBillsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findByName(Role::PARTNER)->givePermissionTo(
            Permission::create(['name' => 'manage bills'])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->where('name', 'manage bills')->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
