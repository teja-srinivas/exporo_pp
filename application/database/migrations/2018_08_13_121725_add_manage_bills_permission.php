<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\BillPolicy;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

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
}
