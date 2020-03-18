<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Investor;
use App\Models\Investment;
use App\Models\Commission;
use App\Models\CommissionType;
use App\Models\CommissionBonus;
use App\Models\ProductContract;
use Tests\Traits\TestsContracts;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use App\Console\Commands\CalculateCommissions;

class CalculateCommissionsTest extends TestCase
{
    use TestsContracts;
    use WithFaker;

    /**
     * @slowThreshold 600
     */
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

        $contract = factory(ProductContract::class)->state('active')->create([
            'user_id' => $user->getKey(),
            'vat_amount' => 0,
        ]);

        $this->calculate();
        $this->assertTrue(Commission::query()->doesntExist());

        $this->createBonuses($contract, $type, [
            CommissionBonus::value(CommissionBonus::TYPE_FIRST_INVESTMENT, 10),
            CommissionBonus::percentage(CommissionBonus::TYPE_FIRST_INVESTMENT, 10),
        ]);

        $this->calculate();
        $this->assertDatabaseHas('commissions', [
            'model_type' => Investment::MORPH_NAME,
            'model_id' => $investment->getKey(),
            'user_id' => $user->getKey(),
            'child_user_id' => 0,
            //'net' => 1000,
            //'gross' => 1000,
            //'bonus' => 10,
            'note_private' => 'Abrechnung gesperrt (01.05.2019)',
            'on_hold' => true,
        ]);
    }

    /** @test */
    public function it_only_calculates_valid_models()
    {
        /** @var User $userWithMissingParent */
        $userWithMissingParent = factory(User::class)->state('accepted')->create([
            'parent_id' => $this->faker->randomNumber(5),
        ]);

        $userWithMissingParent->contracts()->save(
            factory(ProductContract::class)->state('active')->make()
        );

        /** @var Investment $investment */
        $investment = factory(Investment::class)->state('nonRefundable')->create();
        $investment->project()->associate(factory(Project::class)->state('approved')->create());
        $investment->investor()->associate(factory(Investor::class)->create([
            // We only calculate commissions for investments with active investors
            'deleted_at' => now(),
        ]));
        $investment->investor->user()->associate($userWithMissingParent);
        $investment->investor->save();
        $investment->save();

        $this->calculate();

        $this->assertTrue(Commission::query()->doesntExist());
    }

    protected function calculate(): void
    {
        Artisan::call(CalculateCommissions::class);
    }
}
