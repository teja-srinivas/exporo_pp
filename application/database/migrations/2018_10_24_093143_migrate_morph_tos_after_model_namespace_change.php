<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class MigrateMorphTosAfterModelNamespaceChange extends Migration
{
    const FROM = 'App\\';
    const TO = 'App\\Models\\';

    const MODELS = [
        'Agb',
        'Audit',
        'Bill',
        'BonusBundle',
        'Commission',
        'CommissionBonus',
        'CommissionType',
        'Company',
        'Document',
        'Investment',
        'Investor',
        'Permission',
        'Project',
        'Role',
        'Schema',
        'User',
        'UserDetails',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->migrateTable('audits', ['user_type', 'auditable_type'], self::FROM, self::TO);
        $this->migrateTable('model_has_roles', ['model_type'], self::FROM, self::TO);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->migrateTable('audits', ['user_type', 'auditable_type'], self::TO, self::FROM);
        $this->migrateTable('model_has_roles', ['model_type'], self::TO, self::FROM);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function migrateTable(string $table, array $columns, string $fromPrefix, string $toPrefix)
    {
        foreach ($columns as $column) {
            DB::transaction(function () use ($table, $column, $fromPrefix, $toPrefix) {
                foreach (self::MODELS as $model) {
                    DB::table($table)
                        ->where($column, $fromPrefix . $model)
                        ->update([$column => $toPrefix . $model]);
                }
            });
        }
    }
}
