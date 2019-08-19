<?php

namespace Tests\Unit\Helper;

use App\Helper\TagReplacer;
use PHPUnit\Framework\TestCase;

class TagReplacerTest extends TestCase
{

    /**
     * @param  string  $input
     * @param  string  $output
     * @param  array  $replacement
     * @test
     * @dataProvider replacements
     */
    public function it_replaces_tags_inside_template_strings(string $input, string $output, array $replacement): void
    {
        $this->assertEquals($output, TagReplacer::replace($input, $replacement));
    }

    public function replacements(): array
    {
        return [
            [
                'The quick ${color1} ${animal} jumps over the lazy ${animal-2}.',
                'The quick brown fox jumps over the lazy dog.',
                [
                    'color1' => 'brown',
                    'animal' => 'fox',
                    'animal-2' => static function (): string {
                        return 'dog';
                    },
                ],
            ],
            [
                'The ${foo} does not ${exist}',
                'The bar does not ',
                [
                    'foo' => 'bar',
                    'baz' => '',
                ]
            ]
        ];
    }

}
