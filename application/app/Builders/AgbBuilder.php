<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Agb;
use Illuminate\Database\Eloquent\Builder;

class AgbBuilder extends Builder
{
    private const UNKNOWN_TYPE = '__unknown_type__';

    /**
     * Only returns models for the given type.
     * This only supports valid types as specified in the types array.
     *
     * @param  string  $type
     * @return AgbBuilder
     */
    public function forType(string $type): self
    {
        if (! in_array($type, Agb::TYPES)) {
            // Silently fail the query in case it's not a valid type
            $type = self::UNKNOWN_TYPE;
        }

        return $this->where('type', $type);
    }

    /**
     * Only return the currently active model.
     *
     * @param  bool  $value
     * @return AgbBuilder
     */
    public function isDefault(bool $value = true): self
    {
        return $this->where('is_default', $value);
    }
}
