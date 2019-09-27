<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Investor;
use App\Models\Investment;
use App\Models\CommissionType;
use App\Models\CommissionBonus;
use Tests\Traits\TestsContracts;
use App\Services\CalculateCommissionsService;

final class CommissionCalculationTest extends TestCase
{
    use TestsContracts;

    /** @var CalculateCommissionsService */
    protected $service;

    /** @var CommissionType */
    protected $commissionType;

    /** @var User */
    protected $parent;

    /** @var User */
    protected $child;

    /** @var Investment */
    protected $parentInvestment;

    /** @var Investment */
    protected $childInvestment;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(CalculateCommissionsService::class);

        /* @var CommissionType $commissionType */
        $this->commissionType = factory(CommissionType::class)->create();

        /** @var Project $project */
        $project = factory(Project::class)->create([
            'commission_type' => $this->commissionType->getKey(),
            'schema_id' => $this->createSchema(),
            // Use a runtime factor of 1 (12 months)
            'launched_at' => '2017-07-11',
            'payback_max_at' => '2019-07-11',
            'interest_rate' => 5,
            'margin' => 2,
        ]);

        // Create the parent
        $this->parent = factory(User::class)->create();
        $this->parentInvestment = $this->createInvestment($this->parent, $project, false);

        $this->createContract($this->parent, [
            CommissionBonus::percentage(CommissionBonus::TYPE_FURTHER_INVESTMENT, 0.75),
        ]);

        // Create the child
        $this->child = factory(User::class)->create(['parent_id' => $this->parent->getKey()]);
        $this->childInvestment = $this->createInvestment($this->child, $project, true);

        $this->createContract($this->child, [
            CommissionBonus::percentage(CommissionBonus::TYPE_FIRST_INVESTMENT, 1.15),
        ]);
    }

    /**
     * @test
     * @throws
     */
    public function it_calculates_commissions()
    {
        $this->assertEquals(75, $this->service->calculate($this->parentInvestment)['net']);
        $this->assertEquals(115, $this->service->calculate($this->childInvestment)['net']);
    }

    /**
     * @test
     * @throws
     */
    public function it_calculates_overhead_hierarchies()
    {
        // First time fails as we have no overhead bonus on the contract
        $result = $this->service->calculate($this->childInvestment, $this->parent, $this->child);
        $this->assertNull($result['net'], 'No overhead bonuses should result in no money');

        // Add overhead bonuses and try again
        $this->createBonuses($this->parent->contract, $this->commissionType, [
            CommissionBonus::percentage(CommissionBonus::TYPE_FIRST_INVESTMENT, 2.15, true),
        ]);

        $result = $this->service->calculate($this->childInvestment, $this->parent, $this->child);
        $this->assertEquals(100, $result['net']);
    }

    /**
     * @test
     * @throws
     */
    public function it_does_not_calculate_for_invalid_investments()
    {
        /** @var Investment $investment */
        $investment = factory(Investment::class)->make();

        $this->assertNull($this->service->calculate($investment));
    }

    protected function createContract(User $user, array $bonuses): Contract
    {
        /** @var Contract $contract */
        $contract = factory(Contract::class)->state('active')->create([
            'user_id' => $user->getKey(),
            'vat_included' => false,
        ]);

        $this->createBonuses($contract, $this->commissionType, $bonuses);

        return $contract;
    }

    /**
     * @param User $user
     * @param Project $project
     * @param bool $firstInvestment
     * @return Investment
     */
    protected function createInvestment(User $user, Project $project, bool $firstInvestment): Investment
    {
        return factory(Investment::class)->create([
            'investor_id' => factory(Investor::class)->create(['user_id' => $user->getKey()]),
            'is_first_investment' => $firstInvestment,
            'project_id' => $project->getKey(),
            'amount' => 5000,
        ]);
    }
}
