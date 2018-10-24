<?php

use App\Models\Permission;
use App\Models\Role;
use App\Policies\SchemaPolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class AddManageSchemasPermission extends Migration
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
            Permission::create(['name' => SchemaPolicy::PERMISSION])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->where('name', SchemaPolicy::PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
