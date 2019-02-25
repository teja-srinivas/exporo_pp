<?php

namespace App\Observers;

use App\Models\UserDetails;
use App\Models\User;
use App\Traits\Person;

class UserDetailsObserver
{
    /**
     * Handle the user details "created" event.
     *
     * @param  \App\Models\UserDetails  $details
     * @return void
     */
    public function created(UserDetails $details)
    {
        $this->updated($details);
    }

    /**
     * Handle the user details "updated" event.
     *
     * @param  \App\Models\UserDetails  $details
     * @return void
     */
    public function updated(UserDetails $details)
    {
        if (!$details->isDirty(['company']) || $details->isDirty(['display_name'])) {
            return;
        }

        $details->display_name = $this->getNewDisplayName($details);
        $details->save();
    }

    private function getNewDisplayName(UserDetails $details): string
    {
        if ($details->company) {
            return $details->company;
        }

        $user = $details->user;

        return Person::anonymizeName($user->first_name, $user->last_name);
    }
}
