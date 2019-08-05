<?php

use App\Models\Permission;
use App\Policies\CommissionTypePolicy;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;

class RenameProvisionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('provisions', 'commission_bonuses');
        Schema::rename('provision_types', 'commission_types');

        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('provision_type', 'commission_type');
        });

        try {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $permission = Permission::findByName('manage provisionTypes');
            $permission->name = CommissionTypePolicy::PERMISSION;
            $permission->save();
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $ignore) {
            // We're running a fresh migration, ignore this
        }
    }
}
