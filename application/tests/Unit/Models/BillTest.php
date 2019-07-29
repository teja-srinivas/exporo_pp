<?php

namespace Tests\Unit\Models;

use App\Models\Bill;
use Tests\TestCase;

class BillTest extends TestCase
{
    /** @test */
    public function bills_can_be_released()
    {
        $bill = new Bill(['released_at' => now()->addWeek()]);
        $this->assertFalse($bill->isReleased());

        $bill = new Bill(['released_at' => now()->startOfDay()]);
        $this->assertTrue($bill->isReleased());

        $bill = new Bill();
        $this->assertFalse($bill->isReleased());
    }
}
