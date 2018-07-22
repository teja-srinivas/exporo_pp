<?php

namespace Tests\Unit;

use App\Schema;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    /** @test */
    public function it_properly_calculates_values()
    {
        $schema = factory(Schema::class)->make([
            'formula' => 'x * 2 + y',
        ]);

        $this->assertEquals(4, $schema->calculate(1, 2, 0));
    }
}
