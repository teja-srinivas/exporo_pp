<?php

use App\Models\Role;
use App\Policies\SchemaPolicy;

class AddManageSchemasPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        $this->createPermission(SchemaPolicy::PERMISSION)->assignRole(Role::INTERNAL);
    }
}
