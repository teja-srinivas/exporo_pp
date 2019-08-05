<?php

namespace App\Listeners;

use App\Models\Role;
use App\Models\UserDetails;
use App\Policies\BillPolicy;
use App\Events\UserDetailsUpdated;

class UpdateBillingStatusOnUserDetailsUpdate
{
    /**
     * Handle the event.
     *
     * @param  UserDetailsUpdated  $event
     * @return void
     */
    public function handle(UserDetailsUpdated $event)
    {
        $user = $event->details->user;

        if ($user->hasRole(Role::PARTNER) && $this->hasValidBankDetails($event->details)) {
            $user->givePermissionTo(BillPolicy::CAN_BE_BILLED_PERMISSION);
        }
    }

    protected function hasValidBankDetails(UserDetails $details): bool
    {
        // Directly access attributes to not call any decryption logic
        $attributes = $details->getAttributes();

        return ! empty($attributes['bic']) && ! empty($attributes['iban']);
    }
}
