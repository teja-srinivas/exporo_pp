<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\CalculateCommissions;
use App\Models\CommissionBonus;
use App\Models\CommissionType;
use App\Models\Contract;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestsContracts;

class CalculateCommissionsTest extends TestCase
{
    use RefreshDatabase;
    use TestsDatabases;
    use TestsContracts;

    public function test()
    {
        Carbon::setTestNow('2019-05-01');

        /** @var CommissionType $type */
        $type = factory(CommissionType::class)->create();

        /** @var User $user */
        $user = factory(User::class)->state('accepted')->create();

        /** @var Investment $investment */
        $investment = factory(Investment::class)->state('nonRefundable')->create([
            'is_first_investment' => true,
            'amount' => 5000,
            'project_id' => factory(Project::class)->state('approved')->create([
                'commission_type' => $type->getKey(),
                'schema_id' => $this->createSchema()->getKey(),
                // Use a runtime factor of 1 (12 months)
                'launched_at' => '2017-07-11',
                'payback_max_at' => '2019-07-11',
                'interest_rate' => 5,
                'margin' => 2,
            ]),
            'investor_id' => factory(Investor::class)->state('commissionable')->create([
                'user_id' => $user,
            ]),
        ]);

        $contract = factory(Contract::class)->state('active')->create([
            'user_id' => $user->getKey(),
        ]);

        $this->calculate();
        $this->assertTableEmpty('commissions');

        $this->createBonuses($contract, $type, [
            CommissionBonus::value(CommissionBonus::TYPE_FIRST_INVESTMENT, 10),
        ]);

        $this->calculate();
        $this->assertDatabaseHas('commissions', [
            'model_type' => Investment::MORPH_NAME,
            'model_id' => $investment->getKey(),
            'user_id' => $user->getKey(),
            'child_user_id' => 0,
            'net' => 1000,
            'gross' => 1000,
            'bonus' => 10,
            'note_private' => 'Abrechnung gesperrt (01.05.2019)',
            'on_hold' => true,
        ]);
    }

    protected function calculate(): void
    {
        Artisan::call(CalculateCommissions::class);
    }

}
