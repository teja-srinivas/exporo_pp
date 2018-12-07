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
     * @param  \App\Models\UserDetails  $userDetails
     * @return void
     */
    public function created(UserDetails $userDetails)
    {
        $newDisplayName = $this->getNewDisplayName($userDetails);
        if ($userDetails->display_name === $newDisplayName) {
            return;
        }

        $userDetails->display_name = $newDisplayName;
        $userDetails->save();
    }

    /**
     * Handle the user details "updated" event.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return void
     */
    public function updated(UserDetails $userDetails)
    {
        $newDisplayName = $this->getNewDisplayName($userDetails);

        if ($userDetails->display_name === $newDisplayName) {
            return;
        }

        $userDetails->display_name = $newDisplayName;
        $userDetails->save();
    }

    private function getNewDisplayName(UserDetails $userDetails): string
    {
        $user = $userDetails->user;

        if ($userDetails->company) {
            return $userDetails->company;
        }


        return Person::anonymizeName($user->first_name, $user->last_name);
    }

    /**
     * Handle the user details "deleted" event.
     *
     * @param  \App\Models\UserDetails $userDetails
     * @return void
     */
    public function deleted(UserDetails $userDetails)
    {
    }

    /**
     * Handle the user details "restored" event.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return void
     */
    public function restored(UserDetails $userDetails)
    {
    }

    /**
     * Handle the user details "force deleted" event.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return void
     */
    public function forceDeleted(UserDetails $userDetails)
    {
        //
    }
}
