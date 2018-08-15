<?php

use App\Permission;
use App\Policies\BillPolicy;
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

        Role::findByName(Role::INTERNAL)->givePermissionTo(
            Permission::create(['name' => BillPolicy::PERMISSION])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::query()->where('name', BillPolicy::PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
