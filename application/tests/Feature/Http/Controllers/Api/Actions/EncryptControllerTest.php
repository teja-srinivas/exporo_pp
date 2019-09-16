<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\Actions;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EncryptControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_encrypts_the_given_request_data()
    {
        $response = $this->post('/api/actions/encrypt', [
            'foo' => 'bar',
        ]);

        $this->assertEquals('bar', decrypt($response->json('foo')));
    }
}
