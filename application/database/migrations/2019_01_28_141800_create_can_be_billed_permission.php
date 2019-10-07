<?php

use App\Models\Role;
use App\Policies\BillPolicy;

class CreateCanBeBilledPermission extends Migration
{
    const NAME = 'gesperrt';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        Role::create(['name' => self::NAME])->givePermissionTo(
            'view partner dashboard'
        );

        $this->createPermission(BillPolicy::CAN_BE_BILLED_PERMISSION)->assignRole(Role::PARTNER);
    }
}
