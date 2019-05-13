<?php

use App\Models\CommissionBonus;
use App\Models\ContractTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('template_id')->index();
            $table->unsignedSmallInteger('cancellation_days');
            $table->unsignedTinyInteger('claim_years');
            $table->text('special_agreement')->nullable();
            $table->boolean('vat_included')->default(0);
            $table->decimal('vat_amount')->default(0);
            $table->timestamp('accepted_at')->nullable()->comment('the user accepted the conditions');
            $table->timestamp('released_at')->nullable()->comment('if null, this contract is still a draft');
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contract_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body')->nullable();
            $table->timestamps();
        });

        Schema::table('commission_bonuses', function (Blueprint $table) {
            $table->unsignedInteger('contract_id')->default(0)->after('user_id')->index();
        });

        DB::transaction(function () {
            $this->migrateContracts();
        });
    }

    private function migrateContracts()
    {
        /** @var ContractTemplate $template */
        $template = ContractTemplate::query()->create();

        User::query()
            ->whereNull('rejected_at') // Catch both, accepted and pending users
            ->where(function (Builder $builder) {
                // Also migrate legacy data where we do not know if they got verified
                $builder->whereNotNull('email_verified_at');
                $builder->orWhereNotNull('accepted_at');
            })
            ->with('details')
            ->each(function (User $user) use ($template) {
                $contract = $user->contracts()->forceCreate([
                    'user_id' => $user->getKey(),
                    'template_id' => $template->getKey(),
                    'cancellation_days' => 1,
                    'claim_years' => 5,
                    'vat_included' => $user->details->vat_included,
                    'vat_amount' => $user->details->vat_amount,
                    'accepted_at' => $user->accepted_at,
                    'released_at' => $user->accepted_at,
                    'created_at' => $user->accepted_at,
                    'updated_at' => $user->accepted_at,
                ]);

                // We cannot use $user->bonuses() anymore since that already links to the contracts
                CommissionBonus::query()->where('user_id', $user->getKey())->update([
                    'contract_id' => $contract->getKey(),
                ]);
            }, 250);
    }
}
