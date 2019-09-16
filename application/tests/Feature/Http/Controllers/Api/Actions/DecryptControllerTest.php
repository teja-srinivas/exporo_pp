<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\Actions;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DecryptControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_decrypts_the_given_request_data()
    {
        $response = $this->post('/api/actions/decrypt', [
            'foo' => encrypt('bar'),
        ]);

        $this->assertEquals('bar', $response->json('foo'));
    }
}
