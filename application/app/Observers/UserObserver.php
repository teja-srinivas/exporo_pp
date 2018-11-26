<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserDetails;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        $userDetails = $user->details;
        $newDisplayName = $this->getNewDisplayName($userDetails, $user);

        if($userDetails->display_name === $newDisplayName) {
            return;
        }

        $userDetails->display_name = $newDisplayName;
        $userDetails->save();
    }

    private function getNewDisplayName(UserDetails $userDetails, User $user): string
    {

        if($userDetails->company){
            return $userDetails->company;
        }
        $name = $user->first_name[0] . '.' . $user->last_name;
        return $name;
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}