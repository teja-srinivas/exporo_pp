<?php

namespace Tests\Unit\Services;

use App\Models\Contract;
use PHPUnit\Framework\TestCase;
use App\Services\CalculateCommissionsService;

class CalculateCommissionsServiceTest extends TestCase
{
    /**
     * @param float $net
     * @param float $gross
     * @param float $vat
     * @param bool $included
     * @param float $sum
     * @dataProvider netAndGrossValues
     * @test
     */
    public function it_calculates_vat_amounts(float $net, float $gross, float $vat, bool $included, float $sum)
    {
        $service = new CalculateCommissionsService();
        $contract = new Contract([
            'vat_amount' => $vat,
            'vat_included' => $included,
        ]);

        $result = $service->calculateNetAndGross($contract, $sum);

        $this->assertEqualsWithDelta($net, $result['net'], 0.0001);
        $this->assertEqualsWithDelta($gross, $result['gross'], 0.0001);
    }

    public function netAndGrossValues()
    {
        return [
            [100, 100, 0, true, 100],
            [100 / 1.19, 100, 19, true, 100],
            [100, 119, 19, false, 100],
            [0, 0, 19, true, 0],
        ];
    }
}
