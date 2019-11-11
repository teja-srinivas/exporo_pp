<?php

declare(strict_types=1);

namespace Tests\Unit\Helper;

use App\Models\User;
use App\Helper\TagReplacer;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class TagReplacerTest extends TestCase
{
    use CreatesApplication;

    /** @test */
    public function it_wraps_variable_names_as_tags()
    {
        $this->assertEquals('${foo}', TagReplacer::wrap('foo'));
    }

    /** @test */
    public function it_finds_tags_in_text_strings()
    {
        $this->assertEquals([
            'foo', 'bar',
        ], TagReplacer::findTags('This ${foo} is ${bar} test'));
    }

    /** @test */
    public function it_handles_empty_text_values()
    {
        $this->assertEquals([], TagReplacer::findTags(null));
    }

    /** @test */
    public function it_generates_tags_for_the_given_user()
    {
        $this->createApplication();

        $user = new User();
        $user->id = 123;
        $user->first_name = 'John';
        $user->last_name = 'Doe';

        $expected = [
            'partnerid' => 123,
            'partnername' => 'John Doe',
            'reflink' => '?a_aid=123',
        ];

        $tags = TagReplacer::getUserTags($user);

        // Check if we actually cover all the user tags
        $tagKeys = array_keys($tags);
        $expectedKeys = array_keys($expected);

        sort($tagKeys);
        sort($expectedKeys);

        $this->assertEquals($tagKeys, $expectedKeys, 'User tag list is not properly covered');

        // Then see if all the tags are being replaced properly
        $this->assertEquals(
            join(' ', array_values($expected)),
            TagReplacer::replace('${'.join('} ${', array_keys($expected)).'}', $tags)
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
