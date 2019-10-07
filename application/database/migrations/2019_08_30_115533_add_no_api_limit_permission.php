<?php

use App\Models\Permission;

class AddNoApiLimitPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Permission::create([
            'name' => 'features.api.no-limit',
        ]);
    }
}
