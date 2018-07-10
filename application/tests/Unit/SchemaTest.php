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
        $schema = factory(Schema::class)->make();

        $this->assertEquals(4, $schema->calculate(1, 2));
    }
}
