<?php

namespace App\Traits;

/**
 * Contains common helper methods usually added to models
 * that represent some kind of a person.
 *
 * @property string $first_name
 * @property string $last_name
 */
trait Person
{
    /**
     * Creates a displayable string for this resource.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return implode(', ', array_filter([
            trim($this->last_name),
            trim($this->first_name),
        ], function (string $name) {
            return strlen($name) > 0;
        }));
    }
}
