<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LinkBuilder extends Builder
{

    public function visibleForUser(User $user): self
    {
        if ($user->can('update', new Link())) {
            return $this;
        }

        // Only hide links that actually have a mapping of link <-> user.
        // In case no mapping exists, it's still accessible by everybody.
        return $this
            ->whereDoesntHave('users')
            ->orWhereHas('users', static function (UserBuilder $query) use ($user) {
                $query->where('users.id', $user->getKey());
            });
    }
}
