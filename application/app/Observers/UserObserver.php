<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserDetails;
use App\Traits\Person;

class UserObserver
{
    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        $userDetails = $user->details;
        $newDisplayName = $this->getNewDisplayName($userDetails, $user);

        if ($userDetails->display_name === $newDisplayName) {
            return;
        }

        $userDetails->display_name = $newDisplayName;
        $userDetails->save();
    }

    private function getNewDisplayName(UserDetails $userDetails, User $user): string
    {
        if ($userDetails->company) {
            return $userDetails->company;
        }

        return Person::anonymizeName($user->first_name, $user->last_name);
    }
}
