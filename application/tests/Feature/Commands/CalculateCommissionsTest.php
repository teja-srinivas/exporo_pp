<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\CalculateCommissions;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CalculateCommissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test()
    {
        $user = factory(User::class)->states('accepted')->create();

        factory(Investment::class)->states('nonRefundable')->create([
            'project_id' => factory(Project::class)->states('approved')->create(),
            'investor_id' => factory(Investor::class)->states('commissionable')->create([
                'user_id' => $user,
            ]),
        ]);

        Artisan::call(CalculateCommissions::class);

        $this->assertDatabaseMissing('commissions', []);
    }

}
