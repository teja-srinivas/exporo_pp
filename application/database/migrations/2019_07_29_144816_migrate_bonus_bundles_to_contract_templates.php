<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateBonusBundlesToContractTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $this->migrateTemplates();

            Schema::rename('bonus_bundle', 'contract_template_bonus');
            Schema::drop('bundles');

            Schema::table('contract_template_bonus', function (Blueprint $table) {
                $table->renameColumn('bundle_id', 'template_id');
            });
        });
    }

    private function migrateTemplates()
    {
        // We migrate everything manually since the model no longer exists

        // 1. Create a new template per bundle and save the bonus references
        $templatesByBundleId = [];
        $bonusesByBundleId = DB::table('bundles')
            ->get(['name', 'id', Model::CREATED_AT, Model::UPDATED_AT])
            ->mapWithKeys(function (stdClass $row) use (&$templatesByBundleId) {
                $id = $row->id;

                $templateId = DB::table('contract_templates')->insertGetId([
                    'name' => $row->name,
                    'cancellation_days' => 1,
                    'claim_years' => 5,
                    'company_id' => 1,
                    Model::CREATED_AT => $row->{Model::CREATED_AT},
                    Model::UPDATED_AT => $row->{Model::UPDATED_AT},
                ]);

                $templatesByBundleId[$id] = $templateId;

                $bonuses = DB::table('bonus_bundle')
                    ->where('bundle_id', $id)
                    ->pluck('bonus_id');

                return [$id => $bonuses];
            });

        // 2. since we don't want to accidentally merge bonuses with other templates
        //    we simply delete them by their key, not the bundle ID
        $bonusesByBundleId->each(function (Collection $bonuses, int $bundle) use ($templatesByBundleId) {
            DB::table('bonus_bundle')
                ->whereIn('bonus_id', $bonuses)
                ->update(['bundle_id' => $templatesByBundleId[$bundle]]);
        });

        // 3. Remove the excess that no longer has a reference to any bundle.
        DB::table('commission_bonuses')
            ->where('contract_id', 0)
            ->whereNotIn('id', $bonusesByBundleId->flatten())
            ->delete();
    }
}
