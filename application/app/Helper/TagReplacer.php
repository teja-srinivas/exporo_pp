<?php

namespace App\Helper;

use App\Models\User;

class TagReplacer
{
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
        return preg_replace_callback('/\${([\w-_]+?)}/S', static function (array $match) use ($replacements) {
            return value($replacements[$match[1]] ?? '');
        }, $text);
    }
}
