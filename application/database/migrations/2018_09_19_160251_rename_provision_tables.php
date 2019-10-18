<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Policies\CommissionTypePolicy;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

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

        Schema::table('projects', static function (Blueprint $table) {
            $table->renameColumn('provision_type', 'commission_type');
        });

        try {
            $this->clearPermissionCache();
            $permission = Permission::findByName('manage provisionTypes');
            $permission->name = CommissionTypePolicy::PERMISSION;
            $permission->save();
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $ignore) {
            // We're running a fresh migration, ignore this
        }
    }
}
