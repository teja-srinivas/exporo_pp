<?php

declare(strict_types=1);

use App\Models\Permission;
use Illuminate\Support\Str;

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
            ->each(static function (Permission $permission) {
                $permission->name = Str::replaceFirst('management', 'management.contracts', $permission->name);
                $permission->save();
            });
    }
}
