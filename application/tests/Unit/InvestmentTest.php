<?php

namespace Tests\Unit;

use App\Investment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvestmentTest extends TestCase
{
    /** @test */
    public function can_be_refunded()
    {
        $investment = new Investment();
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['paid_at' => now()]);
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['paid_at' => now()->subDays(10)]);
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['paid_at' => now()->subWeeks(2)]);
        $this->assertEquals(false, $investment->isRefundable());

        // TODO
    }
}
