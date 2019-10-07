<?php

use App\Models\Company;
use App\Models\ContractTemplate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddDefaultsToContractTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_templates', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->after('id');
            $table->string('name')->after('id');
            $table->unsignedSmallInteger('cancellation_days')->after('name');
            $table->unsignedTinyInteger('claim_years')->after('cancellation_days');
            $table->text('special_agreement')->nullable()->after('claim_years');
            $table->boolean('vat_included')->default(0)->after('special_agreement');
            $table->decimal('vat_amount')->default(0)->after('vat_included');
        });

        /** @var Company $company */
        $company = Company::query()->first();

        if ($company === null) {
            return;
        }

        ContractTemplate::query()->first()->forceFill([
            'name' => $company->name,
            'company_id' => $company->getKey(),
            'cancellation_days' => 1,
            'claim_years' => 5,
        ])->save();
    }
}
