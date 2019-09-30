<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Investor;
use App\Models\Investment;

class InvestorControllerTest extends TestCase
{
    /** @test */
    public function it_lists_all_investors_with_investment_aggregation()
    {
        /** @var User $user */
        $user = factory(User::class)->state('accepted')->create();

        /** @var Investor $investor */
        $investor = factory(Investor::class)->create([
            'user_id' => $user->getKey(),
            'last_name' => 'Schmidt',
        ]);

        /** @var Investment $paid */
        $paid = $investor->investments()->create(factory(Investment::class)->state('paid')->raw());

        $response = $this->be($user)->get("/users/{$user->getKey()}/investors");
        $response->assertOk();

        // Make sure all the details are there
        $response->assertSee($investor->last_name);
        $response->assertSee(substr(json_encode([
            'investments' => 1,
            'amount' => $paid->amount,
        ]), 1, -1));
    }
}
