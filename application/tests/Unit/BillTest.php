<?php

namespace Tests\Unit;

use App\Models\Bill;
use Tests\TestCase;

class BillTest extends TestCase
{
    /** @test */
    public function bills_can_be_released()
    {
        $bill = new Bill(['released_at' => now()->addWeek()]);
        $this->assertEquals(false, $bill->isReleased());

        $bill = new Bill(['released_at' => now()->startOfDay()]);
        $this->assertEquals(true, $bill->isReleased());

        $bill = new Bill();
        $this->assertEquals(false, $bill->isReleased());
    }
}
