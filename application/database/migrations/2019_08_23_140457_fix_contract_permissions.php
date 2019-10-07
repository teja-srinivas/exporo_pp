<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class FixContractPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->clearPermissionCache();

        DB::table('permissions')
            ->whereIn('name', [
                'features.contracts.update-special-agreement',
                'management.contracts.templates.create',
                'management.contracts.templates.view',
                'management.contracts.templates.update',
                'management.contracts.templates.delete',
                'management.contracts.create',
                'management.contracts.view',
                'management.contracts.update',
                'management.contracts.delete',
            ])
            ->update([
                'protected' => json_encode([Role::PARTNER]),
            ]);

        DB::table('permissions')->whereIn('name', [
            'features.contracts.update',
            'management.commission-bonus-bundles.create',
            'management.commission-bonus-bundles.view',
            'management.commission-bonus-bundles.update',
            'management.commission-bonus-bundles.delete',
        ])->delete();

        Permission::create([
            'name' => 'features.contracts.update-cancellation-period',
            'protected' => Role::PARTNER,
        ]);

        Permission::create([
            'name' => 'features.contracts.update-claim',
            'protected' => Role::PARTNER,
        ]);

        Permission::create([
            'name' => 'features.contracts.update-vat-details',
            'protected' => Role::PARTNER,
        ]);
    }
}
