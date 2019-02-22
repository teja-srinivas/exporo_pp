<?php

namespace Tests\Unit\Services;

use App\Models\UserDetails;
use App\Services\CalculateCommissionsService;
use Tests\TestCase;

class CalculateCommissionsServiceTest extends TestCase
{
    /** @var CalculateCommissionsService */
    protected $service;


    protected function setUp()
    {
        parent::setUp();

        $this->service = $this->app->make(CalculateCommissionsService::class);
    }

    /** @test */
    public function it_calculates_vat_amounts()
    {
        $this->assertNetGross(100, 100, $this->calculateFor(0, true, 100));
        $this->assertNetGross(100/1.19, 100, $this->calculateFor(19, true, 100));
        $this->assertNetGross(100, 119, $this->calculateFor(19, false, 100));
        $this->assertNetGross(0, 0, $this->calculateFor(19, true, 0));
    }

    protected function calculateFor(float $vat, bool $included, float $sum): array
    {
        $details = new UserDetails([
            'vat_amount' => $vat,
            'vat_included' => $included,
        ]);

        return $this->service->calculateNetAndGross($details, $sum);
    }

    protected function assertNetGross(float $net, float $gross, array $result)
    {
        $this->assertEqualsWithDelta($net, $result['net'], 0.0001);
        $this->assertEqualsWithDelta($gross, $result['gross'], 0.0001);
    }
}
