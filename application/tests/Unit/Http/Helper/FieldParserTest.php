<?php

namespace Tests\Unit\Http\Helper;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\Http\Helper\Request\FieldParser;

class FieldParserTest extends TestCase
{
    /** @test */
    public function it_parses_request_fields()
    {
        $first = $this->parseRequest(
            ['name' => 'Hans'],
            ['name', '-age']
        );

        $this->assertTrue($first->filters('name'));
        $this->assertEquals('Hans', $first->get('name')->filter);
        $this->assertEquals('asc', $first->get('name')->order);
        $this->assertEquals('desc', $first->get('age')->order);
    }

    protected function parseRequest(array $filter, array $sort): FieldParser
    {
        return FieldParser::fromRequest(
            Request::create('/', 'GET', [
                'filter' => $filter,
                'sort' => implode(',', $sort),
            ])
        );
    }
}
