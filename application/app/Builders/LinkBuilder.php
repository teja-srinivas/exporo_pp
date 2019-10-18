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
        // In case no mapping exists, it's still accessible by everybody,
        // and thus also not in the list of excluded IDs.
        $notIn = DB::table('link_user')
            ->select('link_id')
            ->where('user_id', '<>', $user->getKey());

        return $this->whereNotIn($this->model->getQualifiedKeyName(), $notIn);
    }
}
