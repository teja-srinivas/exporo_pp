<?php

use App\Models\Permission;
use App\Models\Role;
use App\Policies\BillPolicy;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;


class AddDownloadBillsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create(['name' => BillPolicy::DOWNLOAD_PERMISSION])->assignRole(
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
        Permission::query()->where('name', BillPolicy::PERMISSION)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
