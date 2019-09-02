<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class RenameContractEditPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ([
            'features.contracts.update-cancellation-period',
            'features.contracts.update-claim',
            'features.contracts.update-special-agreement',
            'features.contracts.update-vat-details',
        ] as $name) {
            DB::table('permissions')
                ->where('name', $name)
                ->update([
                    'name' => Str::replaceFirst('features', 'management', $name),
                ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
