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
            $templatesByCompanyId = $this->migrateTemplates();

            $this->migrateContracts($templatesByCompanyId);
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
            'type' => ProductContract::STI_TYPE,
            'cancellation_days' => 0,
            'claim_years' => 0,
        ]);

        return DB::table('contract_templates')
            ->distinct()
            ->select('company_id')
            ->get()
            ->mapWithKeys(function (stdClass $row) {
                $now = now();

                return [$row->company_id => DB::table('contract_templates')->insertGetId([
                    'type' => PartnerContract::STI_TYPE,
                    'company_id' => $row->company_id,
                    'name' => '[Standard] 5 Jahre Anspruch, 1 Tag Kündigungsfrist',
                    'is_default' => true,
                    'cancellation_days' => 1,
                    'claim_years' => 5,
                    'vat_amount' => 0,
                    'vat_included' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])];
            })->all();
    }

    protected function migrateContracts(array $templatesByCompanyId): void
    {
        DB::table('contracts')->update([
            // Keep them all as product contract so the bonus relationship does not break
            'type' => ProductContract::STI_TYPE,
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
                ->selectRaw(DB::raw('\''.PartnerContract::STI_TYPE.'\''))
                ->addSelect(DB::raw($templatesByCompanyId[DB::table('companies')->value('id')]))
                ->addSelect('user_id')
                ->addSelect('cancellation_days')
                ->addSelect('claim_years')
                ->addSelect('special_agreement')
                ->selectRaw(DB::raw(0))
                ->selectRaw(DB::raw(0))
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
}
