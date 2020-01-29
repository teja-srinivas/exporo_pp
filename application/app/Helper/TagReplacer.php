<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\User;

class TagReplacer
{
    public const PATTERN = '/\${([\w\-_]+?)}/S';

    /**
     * Wraps the given variable name as a tag.
     *
     * @param  string  $name
     * @return string
     */
    public static function wrap(string $name): string
    {
        return "\${{$name}}";
    }

    /**
     * Finds and returns a list of all available tags in the given string.
     * The tags are without the pre- and suffix.
     *
     * @param  string|null  $text The raw text
     * @return array A list of all found variables
     */
    public static function findTags(?string $text): array
    {
        if (preg_match_all(self::PATTERN, $text ?? '', $matches) === false) {
            return [];
        }

        return $matches[1];
    }

    /**
     * Returns a list of pre-set tags associated with the given user.
     *
     * @param  User  $user
     * @return array
     */
    public static function getUserTags(User $user): array
    {
        return [
            'reflink' => static function () use ($user) {
                return "?a_aid={$user->id}";
            },
            'partnerid' => static function () use ($user) {
                return (string) $user->getKey();
            },
            'partnername' => static function () use ($user) {
                return implode(' ', array_filter([trim($user->first_name), trim($user->last_name)]));
            },
        ];
    }

    /**
     * Replaces "template tags" inside a text with the replacements
     * specified in the given array.
     *
     * Template tags are wrapped by ${ and }, whereas the content
     * is being used as the array key for the replacement callback.
     *
     * @param  string  $text
     * @param  array  $replacements An array of callbacks or actual values
     * @return string
     */
    public static function replace(string $text, array $replacements): string
    {
        return preg_replace_callback(self::PATTERN, static function (array $match) use ($replacements) {
            return value($replacements[$match[1]] ?? '');
        }, $text);
    }

    /**
     * Add link ref id to link.
     *
     * @param  string  $text
     * @param  int  $id
     * @param  User  $user
     * @return string
     */
    public static function addLinkId(string $text, int $id, User $user): string
    {
        if (!$user->can('features.link-shortener.links')) {
            return $text;
        }

        if (strstr($text, '?')) {
            return $text.'&a_aid_ref='.$id;
        }

        return $text.'?a_aid_ref='.$id;
    }
}
