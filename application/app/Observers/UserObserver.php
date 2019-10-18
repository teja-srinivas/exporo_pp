<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Models\UserDetails;

class UserObserver
{
    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function created(User $user)
    {
        $this->updated($user);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        if (! $user->isDirty(['first_name', 'last_name'])) {
            return;
        }

        $details = $user->details;
        $details->display_name = $this->getNewDisplayName($details, $user);
        $details->save();
    }

    private function getNewDisplayName(UserDetails $details, User $user): string
    {
        if ($details->company) {
            return $details->company;
        }

        return $user->getAnonymousName();
    }
}
