<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

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

        $this->clearPermissionCache();
    }

    private function migrateTable(string $table, array $columns, string $fromPrefix, string $toPrefix)
    {
        foreach ($columns as $column) {
            DB::transaction(static function () use ($table, $column, $fromPrefix, $toPrefix) {
                foreach (self::MODELS as $model) {
                    DB::table($table)
                        ->where($column, $fromPrefix.$model)
                        ->update([$column => $toPrefix.$model]);
                }
            });
        }
    }
}
