<?php

use App\Permission;
use App\Policies\ProjectPolicy;
use App\Role;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class AddManageProjectsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findByName(Role::INTERNAL)->givePermissionTo(
            Permission::create(['name' => ProjectPolicy::PERMISSION])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->where('name', ProjectPolicy::PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
