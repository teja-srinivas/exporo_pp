<?php

namespace Tests\Unit;

use App\Models\Investment;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    /** @test */
    public function it_is_refundable()
    {
        $investment = new Investment();
        $this->assertTrue($investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()]);
        $this->assertTrue($investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()->subDays(10)]);
        $this->assertTrue($investment->isRefundable());

        $investment = new Investment(['acknowledged_at' => now()->subWeeks(2)]);
        $this->assertFalse($investment->isRefundable());

        // TODO
    }
}
