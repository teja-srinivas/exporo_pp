<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Schema;
use FormulaInterpreter\Compiler;

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

        $schema->formula = '(bonus * 2) + Investment';

        $this->assertEquals(143, $schema->calculate([
            'bonus' => 10,
            'InVesTmEnT' => 123,
            'foo' => 42,
        ]));

        $this->assertEquals(20, $schema->calculate([
            'bonus' => 5,
            'investment' => 10,
        ]));

        $schema->formula = 'min(max(pow(sqrt(4), 2), 5), 3)';

        $this->assertEquals(3, $schema->calculate());
    }
}
