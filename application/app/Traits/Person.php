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

    /**
     * Creates a displayable, anonymous name for this person.
     *
     * @return string
     */
    public function getAnonymousName(): string
    {
        return self::anonymizeName($this->first_name, $this->last_name);
    }

    /**
     * Returns an anonymized first name in the form of their initial.
     *
     * @return string
     */
    public function getInitial(): string
    {
        return self::anonymizeFirstName($this->first_name);
    }

    /**
     * Returns an anonymized first name in the form of their initial.
     *
     * @param string $firstName The first name to use
     * @return string
     */
    public static function anonymizeFirstName(?string $firstName): string
    {
        $name = trim($firstName ?? '');

        return strlen($name) > 0 ? mb_strtoupper(mb_substr($name, 0, 1)) . '.' : '';
    }

    /**
     * Anonymizes the given first and last names into a full, displayable name.
     *
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    public static function anonymizeName(?string $firstName, ?string $lastName): string
    {
        return self::anonymizeFirstName($firstName) . ($lastName !== null ? ' ' . trim($lastName) : '');
    }
}
