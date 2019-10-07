<?php

use App\Models\Role;
use App\Models\Permission;
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

        Role::findByName(Role::INTERNAL)->givePermissionTo(
            Permission::create(['name' => SchemaPolicy::PERMISSION])
        );
    }
}
