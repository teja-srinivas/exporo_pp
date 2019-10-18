<?php

declare(strict_types=1);

namespace Tests;

use Drfraker\SnipeMigrations\SnipeMigrations;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use SnipeMigrations;
    use DatabaseTransactions;

    protected function assertStreamEquals(string $expected, Response $response): void
    {
        ob_start();
        $response->sendContent();
        $this->assertEquals($expected, ob_get_clean());
    }
}
