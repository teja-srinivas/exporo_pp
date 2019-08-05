<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use App\Jobs\SendAcceptMail;
use App\Jobs\SendRejectMail;

class SendUserAcceptOrRejectMailOnUpdate
{
    public function handle(UserUpdated $event)
    {
        $user = $event->user;

        if ($user->isDirty('rejected_at') && $user->rejected_at !== null) {
            SendRejectMail::dispatch($user);

            return;
        }

        if ($user->isDirty('accepted_at') && $user->accepted_at !== null) {
            SendAcceptMail::dispatch($user);
        }
    }
}
