<?php

use App\Models\Permission;
use App\Models\Role;
use App\Policies\BonusBundlePolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class AddManageBonusBundlesPermission extends Migration
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
            Permission::create(['name' => BonusBundlePolicy::PERMISSION])
        );
    }
}
