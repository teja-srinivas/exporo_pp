<?php

declare(strict_types=1);

namespace App\Builders\Traits;

use App\Builders\Builder;

/**
 * Represents query helpers for models that have a "user" relationship.
 *
 * @mixin Builder
 */
trait HasUser
{
    /**
     * Only returns investors that also have an associated, active partner user.
     *
     * @param string|null $table
     * @return self
     */
    public function whereHasActiveUser(?string $table = null): self
    {
        return $this
            ->join('users', ($table ?? $this->query->from).'.user_id', 'users.id')
            ->whereNotNull('users.accepted_at')
            ->whereNull('users.rejected_at');
    }
}
