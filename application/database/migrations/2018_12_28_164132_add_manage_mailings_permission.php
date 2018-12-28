<?php

use App\Models\Permission;
use App\Models\Role;
use App\Policies\MailingPolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class AddManageMailingsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create(['name' => MailingPolicy::PERMISSION])->assignRole(
            Role::findByName(Role::ADMIN),
            Role::findByName(Role::INTERNAL)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->where('name', MailingPolicy::PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
