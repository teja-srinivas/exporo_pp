<?php

use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class AddNoApiLimitPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::create([
            'name' => 'features.api.no-limit',
        ]);
    }
}
