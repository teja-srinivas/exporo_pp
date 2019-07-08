<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CommissionBonus;
use App\Models\CommissionType;
use App\Models\Contract;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Project;
use App\Models\Schema;
use App\Models\User;
use App\Services\CalculateCommissionsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommissionCalculationTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $parent;

    /** @var User */
    protected $child;

    /** @var Investment */
    protected $parentInvestment;

    /** @var Investment */
    protected $childInvestment;

    /** @var CalculateCommissionsService */
    protected $service;

    /** @var CommissionType */
    protected $commissionType;

    public function setUp()
    {
        parent::setUp();

        /** @var CommissionType $commissionType */
        $this->commissionType = factory(CommissionType::class)->create();

        /** @var Project $project */
        $project = factory(Project::class)->create([
            'interest_rate' => 5,
            'margin' => 2,
            'commission_type' => $this->commissionType->getKey(),
            'schema_id' => factory(Schema::class)->create(['formula' => 'investment * laufzeit * marge * bonus']),
            'created_at' => '2015-05-06 21:22:39',
            'updated_at' => '2018-07-16 12:56:06',
            'approved_at' => '2015-05-06 21:22:39',
            // Use a runtime factor of 1 (12 months)
            'launched_at' => '2017-07-11',
            'payback_min_at' => '2018-07-11',
        ]);

        $this->parent = factory(User::class)
            ->states('accepted', 'billable')
            ->create();
        $this->parentInvestment = $this->createInvestment($this->parent, $project, false);

        $this->child = factory(User::class)
            ->states('accepted', 'billable')
            ->create(['parent_id' => $this->parent->getKey()]);
        $this->childInvestment = $this->createInvestment($this->child, $project, true);

        $this->createContract($this->parent, 1.25, 0.75, 10);
        $this->createContract($this->child, 1.15, 0.65, 9); // 10% less

        $this->service = $this->app->make(CalculateCommissionsService::class);
    }

    /**
     * @test
     * @throws
     */
    public function it_calculates_commissions()
    {
        $this->assertEquals(750, $this->service->calculate($this->parentInvestment)['net']);
        $this->assertEquals(1150, $this->service->calculate($this->childInvestment)['net']);
    }

    /**
     * @test
     * @throws
     */
    public function it_calculates_parent_child_hierarchies()
    {
        $result = $this->service->calculate($this->childInvestment, $this->parent, $this->child);

        // First time fails as we have no overhead bonus on the contract
        $this->assertNull($result['net'], 'No overhead bonuses should result in no money');

        // Add overhead bonuses and try again
        $this->createBonuses($this->parent->contract, [
            CommissionBonus::percentage(CommissionBonus::TYPE_FIRST_INVESTMENT, 2.15),
        ], true);

        $this->parent->contract->refresh(); // Update cached relationships

        $result = $this->service->calculate($this->childInvestment, $this->parent, $this->child);
        $this->assertEquals(1000, $result['net']);
    }

    protected function createContract(
        User $user,
        float $firstInvestment,
        float $furtherInvestment,
        float $registration
    ): Contract {
        /** @var Contract $contract */
        $contract = factory(Contract::class)->state('active')->create([
            'user_id' => $user->getKey(),
            'vat_included' => false,
        ]);

        $this->createBonuses($contract, [
            CommissionBonus::percentage(CommissionBonus::TYPE_FIRST_INVESTMENT, $firstInvestment),
            CommissionBonus::percentage(CommissionBonus::TYPE_FURTHER_INVESTMENT, $furtherInvestment),
            CommissionBonus::value(CommissionBonus::TYPE_REGISTRATION, $registration),
        ]);

        return $contract;
    }

    protected function createBonuses(Contract $contract, array $bonuses, bool $overhead = false)
    {
        foreach($bonuses as $bonus) {
            $contract->bonuses()->create($bonus + [
                'type_id' => $this->commissionType->getKey(),
                'is_overhead' => $overhead,
            ]);
        }
    }

    protected function createInvestment(User $user, Project $project, bool $firstInvestment): Investment
    {
        return factory(Investment::class)->create([
            'investor_id' => factory(Investor::class)->create(['user_id' => $user->getKey()]),
            'is_first_investment' => $firstInvestment,
            'project_id' => $project->getKey(),
            'amount' => 50000,
        ]);
    }
}
