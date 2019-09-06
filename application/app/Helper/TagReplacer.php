<?php

namespace App\Helper;

class TagReplacer
{
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
