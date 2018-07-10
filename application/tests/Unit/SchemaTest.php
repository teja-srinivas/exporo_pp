<?php

namespace Tests\Unit;

use App\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemaTest extends TestCase
{
    /** @test */
    public function it_properly_calculates_values()
    {
        $schema = new Schema([
            'formula' => '2x + 3(y - x)',
        ]);

        $this->assertEquals(5, $schema->calculate(1, 2));
    }
}
