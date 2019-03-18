<?php

namespace Tests\Unit\Services;

use App\Models\Bill;
use App\Models\Commission;
use App\Models\User;
use App\Policies\BillPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_bills()
    {
        // Create prerequisites
        /** @var User $user */
        $user = factory(User::class)->create();
        $user->givePermissionTo(BillPolicy::CAN_BE_BILLED_PERMISSION);

        // Create test models
        /** @var Commission $valid */
        $valid = factory(Commission::class)->create([
            'user_id' => $user,
            'reviewed_by' => 0,
            'reviewed_at' => now(),
            'net' => 10,
        ]);

        $invalid = collect([
            factory(Commission::class)->create([
                'user_id' => $user,
                'on_hold' => true,
            ]),
            factory(Commission::class)->create([
                'user_id' => $user,
                'rejected_by' => 0,
                'rejected_at' => now()->subDay(),
            ]),
        ]);

        /** @var \App\Services\BillGenerator $generator */
        $generator = app(\App\Services\BillGenerator::class);

        $bills = $generator->generate(now());

        // Compare against our rules
        $this->assertCount(1, $bills);
        $this->assertEmpty($bills->pluck('commissions.id')->intersect($invalid->pluck('id')));

        /** @var Bill $bill */
        $bill = $bills->first();

        $this->assertTrue($bill->user->is($user));
        $this->assertEquals($valid->net, $bill->getTotalNet());
    }
}
