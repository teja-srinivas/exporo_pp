<?php

namespace Tests;

use Drfraker\SnipeMigrations\SnipeMigrations;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, SnipeMigrations;

    protected function assertStreamEquals(string $expected, Response $response): void
    {
        ob_start();
        $response->sendContent();
        $this->assertEquals($expected, ob_get_clean());
    }
}
