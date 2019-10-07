<?php

use App\Models\Role;
use App\Models\Permission;
use App\Policies\BillPolicy;

class AddManageBillsPermission extends Migration
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
            Permission::create(['name' => BillPolicy::PERMISSION])
        );
    }
}
