<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class FixContractPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
                'protected' => json_encode(['partner']),
            ]);

        DB::table('permissions')->whereIn('name', [
            'features.contracts.update',
            'management.commission-bonus-bundles.create',
            'management.commission-bonus-bundles.view',
            'management.commission-bonus-bundles.update',
            'management.commission-bonus-bundles.delete',
        ])->delete();
    }
}
