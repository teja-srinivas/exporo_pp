<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class MoveCommissionBonusPermissionsUnderContractGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::query()
            ->where('name', 'like', 'management.commission-bonuses%')
            ->each(function (Permission $permission) {
                $permission->name = str_replace('management', 'management.contracts', $permission->name);
                $permission->save();
            });
    }
}
