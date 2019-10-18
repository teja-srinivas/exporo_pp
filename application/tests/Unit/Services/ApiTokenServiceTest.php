<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ApiTokenService;

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
