<?php

namespace Tests\Unit;

use App\Schema;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    /** @test */
    public function it_properly_calculates_values()
    {
        $schema = new Schema([
            'formula' => 'x * 2 + y',
        ]);

        $this->assertEquals(4, $schema->calculate([
            'x' => 1,
            'y' => 2,
            'z' => 0,
        ]));

        $schema->formula = 'bonus * 2 + investment';

        $this->assertEquals(143, $schema->calculate([
            'bonus' => 10,
            'investment' => 123,
            'foo' => 42,
        ]));

        $this->assertEquals(20, $schema->calculate([
            'bonus' => 5,
            'investment' => 10,
        ]));
    }
}
