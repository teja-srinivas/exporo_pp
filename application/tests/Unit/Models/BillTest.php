<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Bill;

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
