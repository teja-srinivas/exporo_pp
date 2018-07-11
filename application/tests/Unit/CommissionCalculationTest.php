<?php
declare(strict_types=1);


namespace Tests\Unit;

use App\Investment;
use App\Investor;
use App\Project;
use Tests\TestCase;

final class CommissionCalculationTest extends TestCase
{
    public function setUp()
    {

        for($i = 0; $i < 10; $i++){
            factory(Project::class)->create(
                [
                    'id' => $i,
                    'investor_id' => $i
                ]
            );

            factory(Investor::class)->create(
                [
                    'id' => $i
                ]
            );

            factory(Investment::class, 15)->create(
                [
                    'id' => $i,
                    'investor_id' => $i,
                    'project_id' => $i
                ]
            );
        }


    }

    public function testCreationNotApprovedProject()
    {

    }

}
