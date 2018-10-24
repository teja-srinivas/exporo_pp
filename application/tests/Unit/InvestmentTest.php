<?php

namespace Tests\Unit;

use App\Models\Investment;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    /** @test */
    public function can_be_refunded()
    {
        $investment = new Investment();
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()]);
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()->subDays(10)]);
        $this->assertEquals(true, $investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()->subWeeks(2)]);
        $this->assertEquals(false, $investment->isRefundable());

        // TODO
    }
}
