<?php
declare(strict_types=1);


namespace Tests\Unit;

use App\Console\Commands\calculateCommissions;
use App\Investment;
use App\Schema;
use App\Investor;
use App\Project;
use App\Services\CalculateCommissionsService;
use App\User;
use App\UserDetails;
use function GuzzleHttp\describe_type;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class CommissionCalculationTest extends TestCase
{
    use DatabaseMigrations;
    public function setUp()
    {
        parent::setUp();

            factory(Schema::class)->create();

            factory(Project::class)->create(
                [
                    'id' => 1,
                    'name' => 'Drosselgarten',
                    'interest_rate' => 5,
                    'margin' => 10,
                    'type' => 'project',
                    'schema_id' => 1,
                    'created_at' => '2015-05-06 21:22:39',
                    'updated_at' => '2018-07-16 12:56:06',
                    'launched_at' => '2017-07-11',
                    'payback_min_at' => '2018-07-11',
                    'approved_at' => '2015-05-06 21:22:39'
                ]
            );

            factory(Investor::class)->create(
                [
                    'id' => 1,
                    'user_id' => 1,
                ]
            );

            factory(Investor::class)->create(
                [
                    'id' => 2,
                    'user_id' => 2,
                ]
            );

            factory(User::class)->create(
                [
                    'id' => 1,

                ]
            );

            factory(User::class)->create(
                [
                    'id' => 2,
                ]
            );



            factory(UserDetails::class)->create(
                [
                    'id' => 1,
                    'first_investment_bonus' => 2,
                    'further_investment_bonus' => 1.5,
                    'vat_included' => 0
                ]
            );

            factory(UserDetails::class)->create(
                [
                    'id' => 2,
                    'first_investment_bonus' => 2,
                    'further_investment_bonus' => 1.5,
                    'vat_included' => 1
                ]
            );

            factory(Investment::class)->create(
                [
                    'id' => 1,
                    'investor_id' => 1,
                    'project_id' => 1,
                    'investsum' => 50000
                ]
            );
            factory(Investment::class)->create(
                [
                    'id' => 2,
                    'investor_id' => 2,
                    'project_id' => 1,
                    'investsum' => 50000
                ]
            );
    }


    public function testCommissionCalculation()
    {
        /**
         * @var CalculateCommissionsService
         */
        $job = $this->app->make(CalculateCommissionsService::class);
        $investment = Investment::first();
        $returnValue = $job->calculateCommission($investment);
        $this->assertEquals(303.75, $returnValue['net']);
        $this->assertEquals(375, $returnValue['gross']);
    }

    public function testVatIncluded()
    {
        $job = $this->app->make(CalculateCommissionsService::class);
        $investment = $this->app->make( Investment::class);
        $returnValue = $job->calculateCommission($investment->orderBy('id', 'desc')->first());

        $this->assertEquals(375, $returnValue['net']);
        $this->assertEquals(446.25, $returnValue['gross']);


    }

}
