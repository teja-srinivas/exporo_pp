<?php

declare(strict_types=1);

use App\Models\PartnerContract;
use App\Models\ProductContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SplitUpContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->alterTables();

        DB::transaction(function () {
            $templatesById = $this->migrateTemplates();

            $this->migrateContracts($templatesById);
        });

        $this->cleanUp();
    }

    protected function alterTables(): void
    {
        Schema::table('contracts', static function (Blueprint $table) {
            $table->unsignedInteger('template_id')->nullable()->change();
            $table->string('type')->after('id')->index();

            $table->unsignedSmallInteger('cancellation_days')->default(0)->change();
            $table->unsignedSmallInteger('claim_years')->default(0)->change();
        });

        Schema::table('contract_templates', static function (Blueprint $table) {
            $table->string('type')->after('id')->index();
            $table->boolean('is_default')->after('company_id')->default(false)->index();

            $table->unsignedSmallInteger('cancellation_days')->default(0)->change();
            $table->unsignedSmallInteger('claim_years')->default(0)->change();
        });
    }

    protected function migrateTemplates(): array
    {
        // Define default template in template table
        // We use a sub query here, since a JOIN query did not work
        DB::table('contract_templates')
            ->whereIn('id', DB::table('companies')->select(['default_contract_template_id']))
            ->update(['is_default' => true]);

        // Split up templates into their types
        DB::table('contract_templates')->update([
            'type' => PartnerContract::STI_TYPE
        ]);

        // Keep a map of original template IDs -> product template IDs
        $templates = DB::table('contract_templates')->get()->mapWithKeys(function ($row) {
            return [$row->id => DB::table('contract_templates')->insertGetId([
                'id' => null,
                'type' => ProductContract::STI_TYPE,
                'cancellation_days' => 0,
                'claim_years' => 0,
            ] + (array) $row)];
        })->all();

        DB::table('contract_templates')
            ->where('type', PartnerContract::STI_TYPE)
            ->update([
                'vat_included' => false,
                'vat_amount' => 0,
            ]);

        return $templates;
    }

    protected function migrateContracts(array $templatesById): void
    {
        DB::table('contracts')->update([
            'type' => PartnerContract::STI_TYPE
        ]);

        DB::table('contracts')->insertUsing(
            [
                'type',
                'template_id',
                'user_id',
                'cancellation_days',
                'claim_years',
                'special_agreement',
                'vat_included',
                'vat_amount',
                'accepted_at',
                'released_at',
                'terminated_at',
                'created_at',
                'updated_at',
            ],
            DB::table('contracts')
                ->leftJoinSub($this->createLUT($templatesById), 'templates', 'templates.key', 'contracts.template_id')
                ->selectRaw(DB::raw('\''.ProductContract::STI_TYPE.'\''))
                ->addSelect('templates.value')
                ->addSelect('user_id')
                ->selectRaw(DB::raw(0))
                ->selectRaw(DB::raw(0))
                ->addSelect('special_agreement')
                ->addSelect('vat_included')
                ->addSelect('vat_amount')
                ->addSelect('accepted_at')
                ->addSelect('released_at')
                ->addSelect('terminated_at')
                ->addSelect('created_at')
                ->addSelect('updated_at')
        );

        DB::table('contracts')
            ->where('type', PartnerContract::STI_TYPE)
            ->update([
                'vat_included' => false,
                'vat_amount' => 0,
            ]);
    }

    protected function cleanUp(): void
    {
        Schema::table('companies', static function (Blueprint $table) {
            $table->dropColumn('default_contract_template_id');
        });
    }

    protected function createLUT(array $array): string
    {
        return join(' union all ', array_map(static function ($value, string $key) {
            return '(select '.DB::raw($key).' as "key", '.DB::raw($value).' as "value")';
        }, $array, array_keys($array)));
    }
}
