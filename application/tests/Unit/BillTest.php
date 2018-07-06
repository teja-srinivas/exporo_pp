<?php

namespace Tests\Unit;

use App\Bill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
