<?php

namespace Tests\Unit\Helper;

use App\Models\User;
use App\Helper\TagReplacer;
use PHPUnit\Framework\TestCase;

class TagReplacerTest extends TestCase
{
    /** @test */
    public function it_finds_tags_in_text_strings()
    {
        $this->assertEquals([
            'foo', 'bar'
        ], TagReplacer::findTags('This ${foo} is ${bar} test'));
    }

    /** @test */
    public function it_generates_tags_for_the_given_user()
    {
        $user = new User();
        $user->id = 123;
        $user->first_name = 'John';
        $user->last_name = 'Doe';

        $expected = [
            'partnerid' => 123,
            'partnername' => 'John Doe',
            'reflink' => '?a_aid=123',
        ];

        $this->assertEquals(
            join(' ', array_values($expected)),
            TagReplacer::replace('${'.join('} ${', array_keys($expected)).'}', TagReplacer::getUserTags($user))
        );
    }

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
                ],
            ],
        ];
    }
}
