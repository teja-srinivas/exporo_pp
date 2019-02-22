<?php

namespace Tests\Unit\Service;

use App\Services\ApiTokenService;
use Tests\TestCase;

class ApiTokenServiceTest extends TestCase
{
    /** @test */
    public function it_creates_custom_service_tokens()
    {
        /** @var ApiTokenService $service */
        $service = $this->app->make(ApiTokenService::class);
        $app = 'phpunit';

        $this->assertTrue($service->isValid($app, $service->forService($app)));
    }
}
