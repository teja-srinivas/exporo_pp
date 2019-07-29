<?php

namespace Tests\Unit\Models;

use App\Models\Investment;
use Carbon\Carbon;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    /**
     * @param bool $refundable
     * @param $date
     * @dataProvider refunds
     * @test
     */
    public function it_can_be_refunded_within_a_time_frame(bool $refundable, ?Carbon $date)
    {
        $investment = new Investment(['acknowledged_at' => $date]);
        $this->assertSame($refundable, $investment->isRefundable());
    }

    public function refunds()
    {
        return [
            'fresh' => [true, null],
            'acknowledged' => [true, now()],
            'within range' => [true, now()->subDays(10)],
            'too old' => [false, now()->subDays(15)],
        ];
    }
}
